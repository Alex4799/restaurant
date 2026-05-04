<?php

namespace App\Http\Controllers;

use App\Events\KitchenAlertEvent;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Promotion;
use App\Models\StoreItem;
use App\Models\Table;
use App\Models\Tax;
use App\Models\WebsiteInfo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function list(){
        $order=Order::when(request('search_key'),function($query){
                        $query->where('id',request('search_key'));
                    })->when(request('status')!=null,function($query){
                        $query->where('status',request('status'));
                    })
                    ->orderBy('created_at','desc')
                    ->paginate(20);
        return view('main.admin.order.list',compact('order'));
    }

    public function order(Request $req){
        DB::beginTransaction();
        try {
            $multiCart=WebsiteInfo::where('id',1)->first()->multi_cart;
            if ($multiCart) {
                $cart=Cart::select('carts.*','products.name as product_name','categories.name as category_name','store_items.profit')
                        ->leftJoin('store_items','carts.store_item_id','store_items.id')
                        ->leftJoin('products','store_items.product_id','products.id')
                        ->leftJoin('categories','products.category_id','categories.id')
                        ->where('user_id',Auth::user()->id)
                        ->get();
            }else{
                $cart=json_decode($req->cartProduct);
            }
            $totalPrice=0;
            $profit=0;
            $payleft=$req->subTotal-$req->payAmount;
            $change=$req->payAmount-$req->subTotal;
            $orderData=[
                'user_name'=>$req->userName,
                'user_email'=>$req->userEmail,
                'user_phone'=>$req->userPhone,
                'payment_method'=>$req->paymentMethod,
                'promotion_id'=>$req->promotionId,
                'promotion_price'=>$req->promotionPrice,
                'total_price'=>$totalPrice,
                'subtotal'=>$req->subTotal,
                'pay_amount'=>$req->payAmount,
                'pay_left'=>$payleft<0?0:$payleft,
                'change'=>$change<0?0:$change,
                'tax_id'=>$req->taxId,
                'tax_price'=>$req->taxPrice,
                'profit'=>$profit,
                'currency'=>$req->currency,
                'table'=>$req->table??'Take Away',
                'shop_id'=>$req->shop_id,
                'shop_name'=>$req->shop_name,
                'seller_name'=>Auth::user()->name,
                'status'=>0,
            ];
            // dd($orderData,$req->all());
            $order=Order::create($orderData);
            foreach($cart as $item){
                $storeItem=StoreItem::select('store_items.*','categories.name as category_name')
                                ->leftJoin('products','store_items.product_id','products.id')
                                ->leftJoin('categories','products.category_id','categories.id')
                                ->where('store_items.id',$item->store_item_id)
                                ->lockForUpdate()
                                ->first();
                $totalPrice+=$item->price*$item->qty;
                $profit+=$storeItem->profit*$item->qty;
                if (!$multiCart && $storeItem->instock_type==0) {
                    if ($storeItem->qty>=$item->qty) {
                        $storeItem->decrement('qty',$item->qty);
                        logger('decrement');
                    }else{
                        logger('not enought instock');
                        DB::rollBack();
                        return redirect()->route('admin#cartList')->with(['danger'=>'An error occurred. Please try again.']);
                    }
                }
                $temp=[
                    'order_id'=>$order->id,
                    'store_product_id'=>$item->store_item_id,
                    'product_name'=>$item->product_name,
                    'category_name'=>$storeItem->category_name,
                    'qty'=>$item->qty,
                    'price'=>$item->price,
                    'profit'=>$storeItem->profit,
                    'currency'=>$storeItem->currency,
                    'note'=>$item->note,
                    'status'=>0,
                ];
                $orderItem=OrderItem::create($temp);
                $orderItemData=$orderItem->toArray();
                $orderItemData['table']=$order->table;
                $this->kitchenAlert($orderItemData,$req->shop_id);
            }
            $order->total_price=$totalPrice;
            $order->profit=$profit;
            $order->status=$payleft<=0?1:0;
            $order->save();
            if ($multiCart) {
                Cart::where('user_id',Auth::user()->id)->delete();
            }
            DB::commit();
            return redirect()->route('admin#orderSummary',$order->id);
        } catch (Exception $e) {
            logger($e);
            DB::rollBack();
            return redirect()->route('admin#cartList')->with(['danger'=>'An error occurred. Please try again.']);
        }
    }

    public function orderSummary($id){
        $order=Order::where('id',$id)->first();
        $orderItem=OrderItem::select('order_items.*','products.image')
                    ->leftJoin('store_items','order_items.store_product_id','store_items.id')
                    ->leftJoin('products','store_items.product_id','products.id')
                    ->where('order_id',$id)
                    ->get();

        $paymentMethod=[];
        foreach (json_decode($order->payment_method) as $item) {
            $payment=Payment::where('id',$item->method)->first();
            array_push($paymentMethod,[
                'method'=>$payment->name,
                'amount'=>$item->amount,
            ]);
        }
        $payment=Payment::where('shop_id',0)->orWhere('shop_id',$order->shop_id)->get();
        return view('main.admin.order.summary',compact('order','orderItem','paymentMethod','payment'));
    }

    public function generateInvoice($id){
        $order=Order::select('orders.*','promotions.name as promotion_name','taxes.name as tax_name')
                ->leftJoin('promotions','orders.promotion_id','promotions.id')
                ->leftJoin('taxes','orders.tax_id','taxes.id')
                ->where('orders.id',$id)->first();
        $orderItem=OrderItem::where('order_id',$id)->get();
        $payment=json_decode($order->payment_method);
        $paymentMethod=[];
        $websiteInfo=WebsiteInfo::where('id',1)->first();
        foreach ($payment as $item) {
            // dd($item);
            $paymentName=Payment::where('id',$item->method)->first()->name;
            array_push($paymentMethod,[
                'method'=>$paymentName,
                'amount'=>$item->amount
            ]);
        }
        return view('main.admin.order.invoice',compact('order','orderItem','paymentMethod','websiteInfo'));
    }

    public function orderFinish(Request $req){
        $order=Order::find($req->id);
        if ($order->status==0) {
            $paymentMethod=json_decode($order->payment_method)??[];
            $payleft=$order->pay_left-$req->pay_amount;
            $order->pay_amount+=$req->pay_amount;
            $restaurant=WebsiteInfo::where('id',1)->first()->restaurant;
            if ($restaurant) {
                $orderItem=OrderItem::where('order_id',$order->id)->where('status',0)->first();
                if (isset($orderItem)) {
                    return back()->with(['warning'=>'Some order item is still pending. Pelase make sure order is finished.']);
                }
                }
            if ($payleft<=0) {
                $table=Table::where('name',$order->table)->first();
                $table->status=0;
                $table->save();
            }
            if ($payleft==0) {
                $order->pay_left=0;
                $order->status=1;
                array_push($paymentMethod,['amount'=>$req->pay_amount,'method'=>$req->payment_method]);
                $order->payment_method=json_encode($paymentMethod);
            }else if($payleft>0){
                $order->pay_left=$payleft;
                array_push($paymentMethod,['amount'=>$req->pay_amount,'method'=>$req->payment_method]);
                $order->payment_method=json_encode($paymentMethod);
            }else{
                $order->pay_left=0;
                $order->change=$payleft*-1;
                $order->status=1;
                array_push($paymentMethod,['amount'=>$req->pay_amount,'method'=>$req->payment_method]);
                $order->payment_method=json_encode($paymentMethod);
            }
            $order->save();
            return back()->with(['success'=>'Order finish successful.']);
        }
        return back()->with(['danger'=>'Something wrong. Please try again.']);
    }

    private function kitchenAlert($orderItem,$id){
        event(new KitchenAlertEvent($orderItem,$id));
    }

}
