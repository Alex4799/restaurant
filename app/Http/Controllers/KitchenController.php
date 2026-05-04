<?php

namespace App\Http\Controllers;

use App\Events\WaiterAlertEvent;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KitchenController extends Controller
{
    public function list(){
        $list=OrderItem::select('order_items.*','orders.table')
                    ->leftJoin('orders','order_items.order_id','orders.id')
                    ->when(request('status')!=null,function($query){
                        $query->where('order_items.status',request('status'));
                    })->when(request('today'),function($query){
                        $query->whereDate('order_items.created_at',Carbon::now()->toDateString());
                    })
                    ->orderBy('status')
                    ->paginate(10);
        return view('main.admin.kitchen.list',compact('list'));
    }

    public function lit_waiter(){
        $list=OrderItem::select('order_items.*','orders.table')
                    ->leftJoin('orders','order_items.order_id','orders.id')
                    ->where('order_items.status',3)
                    ->when(request('today'),function($query){
                        $query->whereDate('order_items.created_at',Carbon::now()->toDateString());
                    })
                    ->orderBy('status')
                    ->paginate(10);
        return view('main.admin.kitchen.waiter',compact('list'));
    }

    public function changeStatus($id,$status){
        $orderItem=OrderItem::find($id);
        if ($orderItem->status!=4 && $status==4) {
            $order=Order::find($orderItem->order_id);
            $order->total_price-=($orderItem->price*$orderItem->qty);
            $order->subtotal-=($orderItem->price*$orderItem->qty);
            $order->pay_left-=($orderItem->price*$orderItem->qty);
            $order->save();
        }
        $orderItem->status=$status;
        $orderItem->save();
        if ($status==2) {
            $order=Order::find($orderItem->order_id);
            $orderItemData=$orderItem->toArray();
            $orderItemData['table']=$order->table;
            event(new WaiterAlertEvent($orderItem,$order->shop_id));
        }else if($order->status==2){
            $order=Order::find($orderItem->order_id);
            $orderItemData=$orderItem->toArray();
            $orderItemData['table']=$order->table;
            event(new WaiterAlertEvent($orderItem,$order->shop_id));
        }
        return back()->with(['success'=>'Change status successfully.']);
    }

    public function getData(){
        $data=OrderItem::select(DB::raw('COUNT(id) as orderItemCount'),'status')
                    ->where('status',0)
                    ->groupBy('status')
                    ->get();
        return response()->json($data, 200);
    }
}
