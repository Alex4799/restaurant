<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Currency;
use App\Models\Transfer;
use App\Models\StoreItem;
use App\Models\TransferItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller
{
    public function list(){
        $transfer=Transfer::select('transfers.*','send_store.name as send_store_name','receive_store.name as receive_store_name')
                        ->leftJoin('stores as send_store','transfers.send_store_id','send_store.id')
                        ->leftJoin('stores as receive_store','transfers.receive_store_id','receive_store.id')
                        ->when(request('status'),function($query){
                            $query->where('transfers.status',request('status'));
                        })
                        ->when(request('store_id'),function($query){
                            $query->orWhere('send_store.id',request('store_id'))
                                ->orWhere('receive_store.id',request('store_id'));
                        })
                        ->get();

        $store=Store::select('stores.*','shops.name as shop_name')
                    ->leftJoin('shops','stores.shop_id','shops.id')
                    ->where('stores.active',1)
                    ->get();
        $currency=Currency::get();

        return view('main.admin.transfer.list',compact('transfer','store','currency'));
    }

    public function create(Request $req){
        $this->validation($req);
        $data=$this->changeFormat($req,'create');
        $transfer=Transfer::create($data);
        return redirect()->route('admin#transferView',$transfer->id)->with(['success'=>'Transfer create successful.']);
    }

    public function view($id){
        $transfer=Transfer::select('transfers.*','send_store.name as send_store_name','receive_store.name as receive_store_name')
                        ->leftJoin('stores as send_store','transfers.send_store_id','send_store.id')
                        ->leftJoin('stores as receive_store','transfers.receive_store_id','receive_store.id')
                        ->where('transfers.id',$id)
                        ->first();

        $store=Store::select('stores.*','shops.name as shop_name')
                    ->leftJoin('shops','stores.shop_id','shops.id')
                    ->where('stores.active',1)
                    ->get();
        $currency=Currency::get();

        $transferItem=TransferItem::select('transfer_items.*','products.name as product_name','products.image as image')
                                ->leftJoin('store_items','transfer_items.store_item_id','store_items.id')
                                ->leftJoin('products','store_items.product_id','products.id')
                                ->where('transfer_id',$id)
                                ->get();

        $storeItem=StoreItem::select('store_items.*','products.name as product_name')
                        ->leftJoin('products','store_items.product_id','products.id')
                        ->where('store_id',$transfer->send_store_id)
                        ->where('instock_type',0)
                        ->get();

        return view('main.admin.transfer.view',compact('transfer','store','currency','transferItem','storeItem'));
    }

    public function update(Request $req){
        DB::transaction(function () use ($req) {
            $data=[
                'currency'=>$req->currency,
                'additional_fees'=>$req->additional_fees??0,
                'status'=>$req->status,
            ];
            $transfer=Transfer::findOrFail($req->id);
            if ($req->status==1) {
                TransferItem::where('transfer_id',$req->id)->where('status',0)->update(['status'=>1]);
                $transferItem=TransferItem::select('transfer_items.*','store_items.product_id')
                                        ->leftJoin('store_items','transfer_items.store_item_id','store_items.id')
                                        ->where('transfer_items.transfer_id',$req->id)
                                        ->where('transfer_items.status','!=',2)
                                        ->get();
                foreach ($transferItem as $item) {
                    $storeItem=StoreItem::where('store_id',$transfer->receive_store_id)->where('product_id',$item->product_id)->first();
                    if (isset($storeItem)) {
                        $tempData=[
                            'purchase_price'=>$item->price,
                            'qty'=>$storeItem->qty+$item->qty,
                        ];
                        StoreItem::where('id',$storeItem->id)->update($tempData);
                    }else{
                        $tempData=[
                            'product_id'=>$item->product_id,
                            'purchase_price'=>$item->price,
                            'selling_price'=>$item->price,
                            'profit'=>0,
                            'currency'=>$item->currency,
                            'qty'=>$item->qty,
                            'instock_level'=>10,
                            'store_id'=>$transfer->receive_store_id,
                            'instock_type'=>0,
                            'type'=>0,
                            'active'=>0,
                        ];
                        StoreItem::create($tempData);
                    }
                }
            }elseif($req->status==2){
                $transferItem=TransferItem::select('transfer_items.*','store_items.product_id')
                                        ->leftJoin('store_items','transfer_items.store_item_id','store_items.id')
                                        ->where('transfer_id',$req->id)
                                        ->where('status','!=',2)
                                        ->get();
                if ($transfer->status==1) {
                    foreach ($transferItem as $item) {
                        StoreItem::where('store_id',$transfer->receive_store_id)
                                ->where('product_id',$item->product_id)->decrement('qty',$item->qty);
                        StoreItem::where('id',$item->store_item_id)->increment('qty',$item->qty);
                    }
                }else{
                    foreach ($transferItem as $item) {
                        StoreItem::where('id',$item->store_item_id)->increment('qty',$item->qty);
                    }
                }
            }
            Transfer::where('id',$req->id)->update($data);
        });
        return back()->with(['success'=>"Transfer update successful."]);
    }

    public function delete(Request $req){
        Transfer::where('id',$req->id)->delete();
        return redirect()->route('admin#transferList')->with(['warning'=>'Transfer delete successful']);
    }

    private function validation($req){
        Validator::make($req->all(),[
            'send_store_id'=>'required',
            'receive_store_id'=>'required',
            'currency'=>'required',
        ])->validate();
    }

    private function changeFormat($req,$status){
        return [
            'send_store_id'=>$req->send_store_id,
            'receive_store_id'=>$req->receive_store_id,
            'currency'=>$req->currency,
            'additional_fees'=>$req->additional_fees??0,
            'status'=>$status=='create'?0:$req->status,
        ];
    }
}
