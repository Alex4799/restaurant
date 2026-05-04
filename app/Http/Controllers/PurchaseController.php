<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\StoreItem;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    public function list(){
        $purchase=Purchase::select('purchases.*','stores.name as store_name','suppliers.name as supplier_name')
                            ->when(request('search_key'),function($query){
                                $query->where('purchases.id','like',request('search_key'));
                            })->when(request('store_id'),function($query){
                                $query->where('purchases.store_id',request('store_id'));
                            })->when(request('status')!=null,function($query){
                                $query->where('purchases.status',request('status'));
                            })->when(Auth::user()->shop_id,function($query){
                                $query->where('stores.shop_id',Auth::user()->shop_id);
                            })
                            ->leftJoin('stores','purchases.store_id','stores.id')
                            ->leftJoin('suppliers','purchases.supplier_id','suppliers.id')
                            ->orderBy('created_at','desc')
                            ->get();

        $store=Store::select('stores.*','shops.name as shop_name')
                    ->leftJoin('shops','stores.shop_id','shops.id')
                    ->where('stores.active',1)
                    ->get();
        $supplier=Supplier::get();
        $currency=Currency::get();

        return view('main.admin.purchase.list',compact('purchase','store','supplier','currency'));
    }

    public function create(Request $req){
        $this->validation($req);
        $data=$this->changeFormat($req,'create');
        $purchase=Purchase::create($data);
        return redirect()->route('admin#purchaseView',$purchase->id)->with(['success'=>'Purchase create successful.']);
    }

    public function view($id){
        $purchase=Purchase::select('purchases.*','stores.name as store_name','suppliers.name as supplier_name')
                            ->leftJoin('stores','purchases.store_id','stores.id')
                            ->leftJoin('suppliers','purchases.supplier_id','suppliers.id')
                            ->where('purchases.id',$id)
                            ->first();

        $purchaseItem=PurchaseItem::select('purchase_items.*','products.name as product_name','image')
                                ->leftJoin('products','purchase_items.product_id','products.id')
                                ->where('purchase_id',$id)
                                ->get();

        $product=Product::where('active',1)->get();
        $store=Store::select('stores.*','shops.name as shop_name')
                    ->leftJoin('shops','stores.shop_id','shops.id')
                    ->where('stores.active',1)
                    ->get();
        $supplier=Supplier::get();
        $currency=Currency::get();
        return view('main.admin.purchase.view',compact('purchase','purchaseItem','product','store','supplier','currency'));
    }

    public function update(Request $req){
        $this->validation($req);
        $data=$this->changeFormat($req,'update');
        if ($req->status==1) {
            $purchase=Purchase::where('id',$req->id)->first();
            $purchaseItem=PurchaseItem::where('purchase_id',$req->id)->where('status',1)->get();
            foreach ($purchaseItem as $item) {
                $storeItem=StoreItem::where('store_id',$purchase->store_id)->where('product_id',$item->product_id)->first();
                if (isset($storeItem)) {
                    $tempData=[
                        'purchase_price'=>$item->price,
                        'profit'=>$req->type?0:$storeItem->selling_price-$item->price,
                        'currency'=>$item->currency,
                        'qty'=>$storeItem->qty+$item->qty,
                        'type'=>$item->type?1:0,
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
                        'store_id'=>$purchase->store_id,
                        'instock_type'=>0,
                        'type'=>$item->type?1:0,
                        'active'=>0,
                    ];
                    StoreItem::create($tempData);
                }
            }
        }
        Purchase::where('id',$req->id)->update($data);
        return back()->with(['success'=>'Purchase update successful.']);
    }

    public function delete(Request $req){
        Purchase::where('id',$req->id)->delete();
        PurchaseItem::where('purchase_id',$req->id)->delete();
        return redirect()->route('admin#purchaseList')->with(['warning'=>'Purchase delete successful.']);
    }

    // private function
    private function validation($req){
        Validator::make($req->all(),[
            'supplier_id'=>'required',
            'store_id'=>'required',
            'currency'=>'required',
        ])->validate();
    }

    private function changeFormat($req,$status){
        return [
            'supplier_id'=>$req->supplier_id,
            'store_id'=>$req->store_id,
            'currency'=>$req->currency,
            'status'=>$status=='create'?0:$req->status,
        ];
    }
}
