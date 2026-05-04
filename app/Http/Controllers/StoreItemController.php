<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\StoreItem;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Illuminate\Support\Facades\Validator;

class StoreItemController extends Controller
{
    public function create(Request $req){
        // dd($req->all());
        $this->validation($req,'create');
        $product=StoreItem::where('product_id',$req->product_id)->where('store_id',$req->store_id)->first();
        if (isset($product)) {
            $data=[
                'purchase_price'=>$req->purchase_price,
                'selling_price'=>$req->type?$req->purchase_price:$req->selling_price,
                'profit'=>$req->type?0:$req->selling_price-$req->purchase_price,
                'currency'=>$req->currency,
                'qty'=>$product->qty+$req->qty,
                'instock_level'=>$req->instock_level,
                'instock_type'=>$req->instock_type?1:0,
                'type'=>$req->type?1:0,
                'active'=>$req->active,
                'barcode'=>$req->barcode??$product->barcode,
            ];
            StoreItem::where('id',$product->id)->update($data);
        }else{
            $data=[
                'product_id'=>$req->product_id,
                'purchase_price'=>$req->purchase_price,
                'selling_price'=>$req->type?$req->purchase_price:$req->selling_price,
                'profit'=>$req->type?0:$req->selling_price-$req->purchase_price,
                'currency'=>$req->currency,
                'qty'=>$req->instock_type?1:$req->qty,
                'instock_level'=>$req->instock_type?1:$req->instock_level,
                'store_id'=>$req->store_id,
                'instock_type'=>$req->instock_type?1:0,
                'type'=>$req->type?1:0,
                'active'=>$req->active,
                'barcode'=>$req->barcode,
            ];
            $storeItem=StoreItem::create($data);
        }
        if (!$req->instock_type) {
            $purchase=Purchase::create([
                'supplier_id'=>$req->supplier_id,
                'store_id'=>$req->store_id,
                'currency'=>$req->currency,
                'total_price'=>$req->purchase_price*$req->qty,
                'status'=>1,
            ]);
            PurchaseItem::create([
                'purchase_id'=>$purchase->id,
                'product_id'=>$req->product_id,
                'qty'=>$req->qty,
                'price'=>$req->purchase_price,
                'total_price'=>$req->purchase_price*$req->qty,
                'type'=>$req->type?1:0,
                'currency'=>$req->currency,
                'status'=>1,
            ]);
        }
        return back()->with(['success'=>'Store item create successful.']);
    }

    public function update(Request $req){
        $this->validation($req,'update');
        $product=StoreItem::where('id',$req->id)->first();
        $data=[
            'purchase_price'=>$req->purchase_price,
            'selling_price'=>$req->selling_price,
            'profit'=>$req->selling_price-$req->purchase_price,
            'currency'=>$req->currency,
            'qty'=>$req->qty,
            'instock_level'=>$req->instock_level,
            'instock_type'=>$req->instock_type?1:0,
            'type'=>$req->type?1:0,
            'active'=>$req->active,
            'barcode'=>$req->barcode,
        ];
        StoreItem::where('id',$req->id)->update($data);
        return back()->with(['success'=>'Store item update successful.']);
    }

    public function delete(Request $req){
        $storeItem=StoreItem::find($req->id);
        if ($storeItem->qty==0) {
            $storeItem->delete();
            $message=['warning'=>'Store item delete successful.'];
        }else{
            $message=['danger'=>'In store item delete, its qty must need to be 0'];
        }
        return back()->with($message);
    }

    public function generateBarCode(Request $req){
        $storeProduct=StoreItem::select('store_items.*','products.name as product_name')
                            ->leftjoin('products','store_items.product_id','products.id')
                            ->where('store_items.id',$req->id)->first();
        $count=$req->count>0?$req->count:1;
        // $generator = new BarcodeGeneratorSVG();
        // $barcode = $generator->getBarcode($id, $generator::TYPE_CODE_128);
        $paddedId = $storeProduct->barcode; // Converts "2" to "0002"
        $generator = new BarcodeGeneratorSVG();
        $barcode = $generator->getBarcode($paddedId, $generator::TYPE_CODE_128);


        return view('main.admin.store.barcode',compact('barcode','paddedId','storeProduct','count'));
    }

    // private function
    private function validation($req,$status){
        Validator::make($req->all(),[
            'product_id'=>$status=='create'?'required':'',
            'purchase_price'=>'required',
            'selling_price'=>$req->type?'':'required',
            'currency'=>'required',
            'qty'=>$req->instock_type?'':'required',
            'instock_level'=>$req->instock_type?'':'required',
            'active'=>'required',
        ])->validate();
    }
}
