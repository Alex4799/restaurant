<?php

namespace App\Http\Controllers;

use App\Events\KitchenAlertEvent;
use App\Events\StockChangeEvent;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Promotion;
use App\Models\Store;
use App\Models\StoreItem;
use App\Models\Table;
use App\Models\Tax;
use App\Models\WebsiteInfo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // public function addCart($id){

    //     $userId = auth()->id();

    //     $storeItem = StoreItem::select('store_items.*', 'stores.shop_id')
    //         ->leftJoin('stores', 'store_items.store_id', 'stores.id')
    //         ->where('store_items.id', $id)
    //         ->firstOrFail();

    //     $currentItem = Cart::where('user_id', $userId)
    //         ->where('store_item_id', $id)
    //         ->first();

    //     if ($currentItem) {
    //         return back()->with(['warning' => 'Product is already in cart.']);
    //     }

    //     // Check stock
    //     if ($storeItem->instock_type != 1 && $storeItem->qty <= 0) {
    //         return back()->with(['warning' => 'Product instock is not enough.']);
    //     }

    //     $cartProducts = Cart::select('carts.*', 'stores.shop_id')
    //         ->leftJoin('store_items', 'carts.store_item_id', 'store_items.id')
    //         ->leftJoin('stores', 'store_items.store_id', 'stores.id')
    //         ->where('user_id', $userId)
    //         ->get();

    //     if ($cartProducts->isNotEmpty()) {

    //         $firstCart = $cartProducts->first();

    //         if ($firstCart->shop_id != $storeItem->shop_id) {
    //             return back()->with(['warning' => "You can't choose product from another shop."]);
    //         }

    //         if ($firstCart->currency != $storeItem->currency) {
    //             return back()->with(['warning' => "You can't choose product with another currency."]);
    //         }
    //     }

    //     DB::transaction(function () use ($storeItem, $userId) {
    //         $data = $this->changeFormat($storeItem);
    //         $data['user_id'] = $userId;

    //         Cart::create($data);

    //         if ($storeItem->instock_type != 1) {
    //             $storeItem->decrement('qty', 1);
    //         }
    //     });
    //     if ($storeItem->instock_type!=1) {
    //         $this->stockChangeEvent($storeItem->id,$storeItem->qty);
    //     }
    //     return back()->with(['success' => 'Add to cart successful.']);
    // }

    public function addCart(Request $req){
        try {
            DB::transaction(function() use($req) {
                $storeItem = StoreItem::select('store_items.*', 'stores.shop_id')
                            ->leftJoin('stores', 'store_items.store_id', 'stores.id')
                            ->where('store_items.id', $req->id)
                            ->lockForUpdate()
                            ->firstOrFail();

                $exists = Cart::where('store_item_id', $storeItem->id)
                                ->where('user_id', auth()->id())
                                ->exists();

                if ($exists) {
                    throw new \Exception('Item already in cart');
                }
                if ($storeItem->instock_type != 1 && $storeItem->qty <= 0) {
                    throw new \Exception('No enough stock');
                }else{
                    $data = $this->changeFormat($storeItem);
                    Cart::create($data);
                    if ($storeItem->instock_type != 1) {
                        $storeItem->decrement('qty', 1);
                        $this->stockChangeEvent($storeItem->id,$storeItem->qty);
                    }
                }
            });
            $cart=Cart::select('carts.*','store_items.selling_price','products.name as product_name',
                            'store_items.qty as instock','store_items.instock_type','stores.id as store_id')
                            ->leftJoin('store_items','carts.store_item_id','store_items.id')
                            ->leftJoin('products','store_items.product_id','products.id')
                            ->leftJoin('stores','store_items.store_id','stores.id')
                            ->where('user_id',Auth::user()->id)->get();
            $message=[
                'status'=>'success',
                'message'=>'Add to cart successful',
                'cart'=>$cart,
            ];
            return response()->json($message, 200);
        } catch (\Exception $e) {
            logger($e);
            $message=[
                'status'=>'failed',
                'message'=>$e->getMessage()??'Something is wrong. Please try again',
            ];
            return response()->json($message, 200);
        }
    }

    public function addCartBarcode(Request $req){
        $userId = auth()->id();
        $scannedId = $req->id;
        $storeItem = StoreItem::select('store_items.*', 'stores.shop_id')
            ->leftJoin('stores', 'store_items.store_id', 'stores.id')
            ->where('store_items.barcode',$scannedId)
            ->firstOrFail();

        $currentItem = Cart::where('user_id', $userId)
            ->where('store_item_id', $storeItem->id)
            ->first();

        if ($currentItem) {
            return back()->with(['warning' => 'Product is already in cart.']);
        }

        // Check stock
        if ($storeItem->instock_type != 1 && $storeItem->qty <= 0) {
            return back()->with(['warning' => 'Product instock is not enough.']);
        }

        $cartProducts = Cart::select('carts.*', 'stores.shop_id')
            ->leftJoin('store_items', 'carts.store_item_id', 'store_items.id')
            ->leftJoin('stores', 'store_items.store_id', 'stores.id')
            ->where('user_id', $userId)
            ->get();

        if ($cartProducts->isNotEmpty()) {

            $firstCart = $cartProducts->first();

            if ($firstCart->shop_id != $storeItem->shop_id) {
                return back()->with(['warning' => "You can't choose product from another shop."]);
            }

            if ($firstCart->currency != $storeItem->currency) {
                return back()->with(['warning' => "You can't choose product with another currency."]);
            }
        }

        DB::transaction(function () use ($storeItem, $userId) {
            $data = $this->changeFormat($storeItem);
            $data['user_id'] = $userId;

            Cart::create($data);

            if ($storeItem->instock_type != 1) {
                $storeItem->decrement('qty', 1);
            }
        });
        if ($storeItem->instock_type!=1) {
            $this->stockChangeEvent($storeItem->id,$storeItem->qty);
        }
        return back()->with(['success' => 'Add to cart successful.']);

    }

    public function removeCart(Request $req){
        try {
            DB::beginTransaction();
            $cart=Cart::findOrFail($req->id);
            $storeItem=StoreItem::lockForUpdate()->findOrFail($cart->store_item_id);
            if ($storeItem->instock_type==0) {
                $storeItem->increment('qty',$cart->qty);
                $this->stockChangeEvent($storeItem->id,$storeItem->qty);
            }
            $cart->delete();
            DB::commit();
            $message=[
                'status'=>'success',
                'message'=>'Remove cart successful',
            ];
            return response()->json($message, 200);
        } catch (\Exception $e) {
            logger($e);
            $message=[
                'status'=>'failed',
                'message'=>$e->getMessage()??'Something is wrong. Please try again',
            ];
            return response()->json($message, 200);
        }
    }

    public function removeCartAll(){
        try {
            $cart=Cart::where('user_id',Auth::user()->id)->get();
            foreach ($cart as $item) {
                $storeItem=StoreItem::lockForUpdate()->findOrFail($item->store_item_id);
                if ($storeItem->instock_type==0) {
                    $storeItem->increment('qty',$item->qty);
                    $this->stockChangeEvent($storeItem->id,$storeItem->qty);
                }
            }
            Cart::where('user_id',Auth::user()->id)->delete();
            $message=[
                'status'=>'success',
                'message'=>'Remove all cart successful.',
            ];
            return response()->json($message, 200);
        } catch (\Exception $e) {
            logger($e);
            $message=[
                'status'=>'failed',
                'message'=>$e->getMessage()??'Something is wrong. Please try again',
            ];
            return response()->json($message, 200);
        }
    }

    public function cartList(){
        $product=StoreItem::select('store_items.*','products.name as product_name','products.image as product_image')
                        ->leftJoin('products','store_items.product_id','products.id')
                        ->leftJoin('categories','products.category_id','categories.id')
                        ->leftJoin('stores','store_items.store_id','stores.id')
                        ->when(Auth::user()->shop!=null,function($query){
                            $query->where('stores.shop_id',Auth::user()->shop);
                        })
                        ->where('store_items.active',1)
                        ->where('store_items.type',0)
                        // ->where('store_items.qty','>',0)
                        ->when(request('category'),function($query){
                            $query->where('categories.name',request('category'));
                        })->when(request('search_key'),function($query){
                            $query->where('products.name','like','%'.request('search_key').'%');
                        })->get();
        $category=Category::get();
        $cartProduct=Cart::select('carts.*','store_items.selling_price','products.name as product_name',
                            'store_items.qty as instock','store_items.instock_type','stores.id as store_id')
                            ->leftJoin('store_items','carts.store_item_id','store_items.id')
                            ->leftJoin('products','store_items.product_id','products.id')
                            ->leftJoin('stores','store_items.store_id','stores.id')
                            ->where('user_id',Auth::user()->id)->get();

        $websiteInfo=WebsiteInfo::where('id',1)->first();
        if ($websiteInfo->multi_cart) {
            $table=Table::where('shop_id',Auth::user()->shop)->get();
            return view('main.admin.cart.multi_cart',compact('product','category','cartProduct','table'));
        }else{
            return view('main.admin.cart.single_cart',compact('product','category','cartProduct'));
        }
    }

    public function getCartData(){
        $cartProduct=Cart::where('user_id',Auth::user()->id)->get();
        return response()->json($cartProduct, 200);
    }

    public function cartChangeQty(Request $req){
        try {
            DB::beginTransaction();
            $cart=Cart::lockForUpdate()->findOrFail($req->id);
            $storeItem=StoreItem::lockForUpdate()->findOrFail($cart->store_item_id);
            if ($req->status=='plus') {
                if ($storeItem->qty>0 || $storeItem->instock_type==1) {
                    $cart->qty++;
                    if ($storeItem->instock_type!=1) {
                        $storeItem->qty--;
                    }
                }else{
                    throw new \Exception('No enough stock');
                }
            }else{
                if ($cart->qty>1) {
                    $cart->qty--;
                    if ($storeItem->instock_type!=1) {
                        $storeItem->qty++;
                    }
                }
            }
            $storeItem->save();
            $cart->save();
            DB::commit();
            if ($storeItem->instock_type!=1) {
                $this->stockChangeEvent($storeItem->id,$storeItem->qty);
            }
            return response()->json(['status'=>'success','qty'=>$cart->qty,'price'=>$cart->price*$cart->qty], 200);
        } catch (\Exception $e) {
            logger($e);
            $message=[
                'status'=>'failed',
                'message'=>$e->getMessage()??'Something is wrong. Please try again',
            ];
            return response()->json($message, 200);
        }
    }

    public function updateNote(Request $req){
        try {
            DB::beginTransaction();
            $cart=Cart::lockForUpdate()->where('id',$req->id)->update(['note'=>$req->note]);
            DB::commit();
            $message=[
                'status'=>'success',
                'message'=>'Update to cart successful',
            ];
            return response()->json($message, 200);
        } catch (\Exception $e) {
            logger($e);
            $message=[
                'status'=>'failed',
                'message'=>$e->getMessage()??'Something is wrong. Please try again',
            ];
            return response()->json($message, 200);
        }
    }

    public function getStoreItem(){
        $product=StoreItem::select('store_items.*','products.name as product_name','products.image as product_image')
                ->leftJoin('products','store_items.product_id','products.id')
                ->leftJoin('categories','products.category_id','categories.id')
                ->leftJoin('stores','store_items.store_id','stores.id')
                ->when(Auth::user()->shop!=null,function($query){
                    $query->where('stores.shop_id',Auth::user()->shop);
                })
                ->where('store_items.active',1)
                ->where('store_items.type',0)
                ->where('store_items.qty','>',0)
                ->when(request('category'),function($query){
                    $query->where('categories.name',request('category'));
                })->when(request('search_key'),function($query){
                    $query->where('products.name','like','%'.request('search_key').'%');
                })->get();
        return response()->json($product, 200);
    }

    public function createCart(Request $req){
        // dd($req->all());
        DB::beginTransaction();
        try {
            $restaurant=WebsiteInfo::where('id',1)->first()->restaurant;
            $cartProduct=json_decode($req->cart);
            if ($restaurant && $req->table!='Take Away') {
                $order=Order::where('table',$req->table)->where('status',0)->first();
                $storeId=$cartProduct[0]->store_id;
                $shop=Store::select('shops.*')
                            ->where('stores.id',$storeId)
                            ->leftJoin('shops','stores.shop_id','shops.id')
                            ->first();
                if (empty($order)) {
                    $data=[
                        'payment_method'=>'[]',
                        'shop_id'=>$shop->id,
                        'shop_name'=>$shop->name,
                        'table'=>$req->table,
                        'status'=>0,
                    ];
                    $order=Order::create($data);
                    Table::where('name',$req->table)->update(['status'=>1]);
                }
                $totalPrice=0;
                $profit=0;
                foreach($cartProduct as $item){
                    $storeItem=StoreItem::select('store_items.*','categories.name as category_name')
                                    ->leftJoin('products','store_items.product_id','products.id')
                                    ->leftJoin('categories','products.category_id','categories.id')
                                    ->where('store_items.id',$item->store_item_id)
                                    ->lockForUpdate()
                                    ->first();
                    $totalPrice+=$item->price*$item->qty;
                    $profit+=$storeItem->profit*$item->qty;
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
                    $orderItemData['table']=$req->table;
                    $this->kitchenAlert($orderItemData,$shop->id);
                }
                if (empty($order)) {
                    $order->total_price=$totalPrice;
                    $order->subtotal=$totalPrice;
                    $order->profit=$profit;
                    $order->pay_left=$totalPrice;
                }else{
                    $order->total_price+=$totalPrice;
                    $order->subtotal+=$totalPrice;
                    $order->profit+=$profit;
                    $order->pay_left+=$totalPrice;
                }
                $order->save();
                Cart::where('user_id',Auth::user()->id)->delete();
                DB::commit();
                return redirect()->route('admin#cartList')->with(['success'=>'Order create successful.']);
            }else{
                $promotion=Promotion::where('active',1)->get();
                $tax=Tax::where('active',1)->get();
                $table=Table::where('shop_id',Auth::user()->shop)->get();
                $payment=Payment::where('active',1)->when(Auth::user()->shop_id,function($query){
                            $query->where('shop_id',Auth::user()->shop);
                        })->orWhere('shop_id',null)->get();
                $cartProduct=json_decode($req->cart);
                $storeId=$cartProduct[0]->store_id;
                $shop=Store::select('shops.*')
                            ->where('stores.id',$storeId)
                            ->leftJoin('shops','stores.shop_id','shops.id')
                            ->first();
                DB::commit();
                return view('main.admin.cart.checkout',compact('cartProduct','promotion','tax','payment','shop','table'));
            }
        } catch (Exception $e) {
            logger($e);
            DB::rollBack();
            return redirect()->route('admin#cartList')->with(['danger'=>'An error occurred. Please try again.']);
        }
    }

    public function checkOut(){
        $promotion=Promotion::where('active',1)->get();
        $tax=Tax::where('active',1)->get();
        $table=Table::where('shop_id',Auth::user()->shop)->get();
        $payment=Payment::where('active',1)->when(Auth::user()->shop,function($query){
                    $query->where('shop_id',Auth::user()->shop);
                })->orWhere('shop_id',null)->get();
        $cartProduct=Cart::select('carts.*','store_items.selling_price','products.name as product_name','store_items.qty as instock','store_items.store_id')
                        ->leftJoin('store_items','carts.store_item_id','store_items.id')
                        ->leftJoin('products','store_items.product_id','products.id')
                        ->where('user_id',Auth::user()->id)->get();
        $storeId=$cartProduct[0]->store_id;
        $shop=Store::select('shops.*')
                    ->where('stores.id',$storeId)
                    ->leftJoin('shops','stores.shop_id','shops.id')
                    ->first();
        return view('main.admin.cart.checkout',compact('cartProduct','promotion','tax','payment','shop','table'));
    }

    // private function
    private function changeFormat($storeItem){
        return [
            'user_id'=>Auth::user()->id,
            'store_item_id'=>$storeItem->id,
            'qty'=>1,
            'price'=>$storeItem->selling_price,
            'currency'=>$storeItem->currency,
        ];
    }

    private function stockChangeEvent($id,$qty){
        event(new StockChangeEvent($id,$qty));
    }

    private function kitchenAlert($orderItem,$id){
        logger($orderItem);
        event(new KitchenAlertEvent($orderItem,$id));
    }

}
