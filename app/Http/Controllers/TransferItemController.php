<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\StoreItem;
use App\Models\TransferItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransferItemController extends Controller
{
    public function create(Request $req){
        $this->validation($req);
        $storeItem=StoreItem::where('id',$req->store_item_id)->first();
        if ($storeItem->qty>=$req->qty) {
            $data=[
                'transfer_id'=>$req->transfer_id,
                'store_item_id'=>$req->store_item_id,
                'qty'=>$req->qty,
                'price'=>$storeItem->selling_price,
                'total_price'=>$storeItem->selling_price*$req->qty,
                'currency'=>$storeItem->currency,
                'status'=>$req->status,
            ];
            TransferItem::create($data);
            StoreItem::where('id',$req->store_item_id)->update(['qty'=>$storeItem->qty-$req->qty]);
            $this->calculateTransferTotal($req->transfer_id);
            return back()->with(['success'=>'Transfer item create successful.']);
        }else{
            return back()->with(['danger'=>"Max qty of store item is less than or equal $storeItem->qty"]);
        }
    }

    public function update(Request $req){
        $this->validation($req);
        $transferItem=TransferItem::where('id',$req->id)->first();
        if ($req->status==2) {
            $transferItem=TransferItem::where('id',$req->id)->first();
            StoreItem::where('id',$transferItem->store_item_id)->increment('qty',$transferItem->qty);
        }else{
            $qtyDifferent=$req->qty-$transferItem->qty;
            if ($qtyDifferent!=0) {
                $storeItem=StoreItem::where('id',$transferItem->store_item_id)->first();
                if ($qtyDifferent>0) {
                    if ($storeItem->qty>=$qtyDifferent) {
                        StoreItem::where('id',$transferItem->store_item_id)->update(['qty'=>$storeItem->qty-$qtyDifferent]);
                    }else{
                        return back()->with(['danger'=>"You can't add this much. Max qty of add store item is less than or equal $storeItem->qty"]);
                    }
                }else{
                    StoreItem::where('id',$transferItem->store_item_id)->update(['qty'=>$storeItem->qty-$qtyDifferent]);
                }
            }
        }
        $data=[
            'qty'=>$req->qty,
            'total_price'=>$transferItem->price*$req->qty,
            'status'=>$req->status,
        ];
        TransferItem::where('id',$req->id)->update($data);
        $this->calculateTransferTotal($transferItem->transfer_id);
        return back()->with(['success'=>'Transfer item update successful.']);
    }

    public function delete(Request $req){
        $transferItem=TransferItem::where('id',$req->id)->first();
        if ($transferItem->status!=2) {
            StoreItem::where('id',$transferItem->store_item_id)->increment('qty',$transferItem->qty);
        }
        TransferItem::where('id',$req->id)->delete();
        $this->calculateTransferTotal($transferItem->transfer_id);
        return back()->with(['success'=>'Transfer item delete successful']);
    }

    // private function
    private function validation($req){
        Validator::make($req->all(),[
            'store_item_id'=>'required',
            'qty'=>'required',
            'status'=>'required',
        ])->validate();
    }

    private function calculateTransferTotal($id){
        $total=TransferItem::where('transfer_id',$id)->where('status','!=',2)->sum('total_price');
        Transfer::where('id',$id)->update(['total_price'=>$total]);
    }
}
