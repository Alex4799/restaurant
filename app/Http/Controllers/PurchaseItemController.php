<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseItemController extends Controller
{
    public function create(Request $req){
        $this->validation($req);
        $data=$this->changeFormat($req);
        $purchaseItem=PurchaseItem::create($data);
        $this->calculatePurchaseTotal($purchaseItem->purchase_id);
        return back()->with(['success'=>'Purchase item create successful.']);
    }

    public function update(Request $req){
        $this->validation($req);
        $data=$this->changeFormat($req);
        PurchaseItem::where('id',$req->id)->update($data);
        $this->calculatePurchaseTotal($req->purchase_id);
        return back()->with(['success'=>'Purchase item update successful.']);
    }

    public function delete(Request $req){
        $purchaseItem=PurchaseItem::where('id',$req->id)->first();
        PurchaseItem::where('id',$req->id)->delete();
        $this->calculatePurchaseTotal($purchaseItem->purchase_id);
        return back()->with(['warning'=>'Purchase item delete successful.']);
    }

    // private function
    private function validation($req){
        Validator::make($req->all(),[
            'purchase_id'=>'required',
            'product_id'=>'required',
            'qty'=> 'required|integer|min:1',
            'price'=> 'required|numeric|min:1',
            'currency'=>'required',
            'status'=>'required',
        ])->validate();
    }

    private function changeFormat($req){
        return [
            'purchase_id'=>$req->purchase_id,
            'product_id'=>$req->product_id,
            'qty'=>$req->qty,
            'price'=>$req->price,
            'total_price'=>$req->qty*$req->price,
            'type'=>$req->type?1:0,
            'currency'=>$req->currency,
            'status'=>$req->status,
        ];
    }

    private function calculatePurchaseTotal($id){
        $total=PurchaseItem::where('purchase_id',$id)->where('status',1)->sum('total_price');
        Purchase::where('id',$id)->update(['total_price'=>$total]);
    }
}
