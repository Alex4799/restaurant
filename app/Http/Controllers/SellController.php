<?php

namespace App\Http\Controllers;

use App\Events\WaiterAlertEvent;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Promotion;
use App\Models\Table;
use App\Models\Tax;
use App\Models\WebsiteInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellController extends Controller
{
    public function table_waiter(){
        $table=Table::where('shop_id',Auth::user()->shop)->get();
        foreach ($table as $key=>$item) {
            $order=Order::where('table',$item->name)->where('status',0)->first();
            if (isset($order)) {
                $orderItem=OrderItem::where('order_id',$order->id)->where('status',2)->get();
                $table[$key]['order']='Have Order';
                $table[$key]['kitchen_finished_order']=count($orderItem);
            }else{
                $table[$key]['order']='No Order';
            }
        }
        return view('main.admin.waiter.table',compact('table'));
    }

    public function tableView_waiter($id){
        $table=Table::where('id',$id)->first();
        $order=Order::where('table',$table->name)->where('status',0)->first();
        if (isset($order)) {
            $temp=OrderItem::select('order_items.*','products.image')
                    ->leftJoin('store_items','order_items.store_product_id','store_items.id')
                    ->leftJoin('products','store_items.product_id','products.id')
                    ->where('order_id',$order->id)
                    ->get();
            $order['orderItem']=$temp;
        }
        $promotion=Promotion::where('shop_id',Auth::user()->shop)->where('active',1)->get();
        $tax=Tax::where('active',1)->get();
        $payment=Payment::where('active',1)->where('shop_id',Auth::user()->id)->get();

        return view('main.admin.waiter.view',compact('table','order','promotion','tax','payment'));
    }

    public function tableFinish_waiter(Request $req){
        $order=Order::find($req->id);
        if ($order->status==0) {
            $restaurant=WebsiteInfo::where('id',1)->first()->restaurant;
            if ($restaurant) {
                $orderItem=OrderItem::where('order_id',$order->id)->where('status',0)->first();
                if (isset($orderItem)) {
                    return back()->with(['warning'=>'Some order item is still pending. Pelase make sure order is finished.']);
                }
            }
                $paymentMethod=json_decode($order->payment_method)??[];
                $order->promotion_id=$req->promotion_id;
                $order->promotion_price=$req->promotion_price;
                $order->tax_id=$req->tax_id;
                $order->tax_price=$req->tax_price;
                $payleft=$req->payleft-$req->pay_amount;
                $order->pay_left=$req->payleft;
                $order->subtotal=$order->total_price-$req->promotion_price+$req->tax_price;
                $order->pay_amount+=$req->pay_amount;
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
            return redirect()->route('admin#orderSummary',$order->id)->with(['success'=>'Order finish successful.']);
        }
        return back()->with(['danger'=>'Something wrong. Please try again.']);
    }

    public function getData(){
        $data=OrderItem::select(DB::raw('COUNT(id) as orderItemCount'),'status')
                    ->where('status',2)
                    ->groupBy('status')
                    ->get();
        return response()->json($data, 200);
    }

    public function changeReceive($id){
        $orderItem=OrderItem::find($id);
        $orderItem->status=3;
        $orderItem->save();
        $order=Order::find($orderItem->order_id);
        $orderItemData=$orderItem->toArray();
        $orderItemData['table']=$order->table;
        event(new WaiterAlertEvent($orderItem,$order->shop_id));
        return back()->with(['success'=>'Change Order Item Status Successful.']);
    }

    // public function cart_waiter($id){
    //     $product=StoreItem::select('store_items.*','products.name as product_name','products.image as product_image')
    //                     ->leftJoin('products','store_items.product_id','products.id')
    //                     ->leftJoin('categories','products.category_id','categories.id')
    //                     ->leftJoin('stores','store_items.store_id','stores.id')
    //                     ->when(Auth::user()->shop!=null,function($query){
    //                         $query->where('stores.shop_id',Auth::user()->shop);
    //                     })
    //                     ->where('store_items.active',1)
    //                     ->where('store_items.type',0)
    //                     ->where('store_items.qty','>',0)
    //                     ->when(request('category'),function($query){
    //                         $query->where('categories.name',request('category'));
    //                     })->when(request('search_key'),function($query){
    //                         $query->where('products.name','like','%'.request('search_key').'%');
    //                     })->get();
    //     $category=Category::get();
    //     $cartProduct=Cart::select('carts.*','store_items.selling_price','products.name as product_name',
    //                         'store_items.qty as instock','store_items.instock_type','stores.id as store_id')
    //                         ->leftJoin('store_items','carts.store_item_id','store_items.id')
    //                         ->leftJoin('products','store_items.product_id','products.id')
    //                         ->leftJoin('stores','store_items.store_id','stores.id')
    //                         ->where('user_id',Auth::user()->id)->get();

    //     return view('main.admin.cart.multi_cart',compact('product','category','cartProduct'));
    // }
}
