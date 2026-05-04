<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Supplier;
use App\Models\StoreItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function create(Request $req){
        $this->validation($req,'create');
        $data=$this->changeFormat($req,'create');
        $store=Store::create($data);
        return redirect()->route('admin#storeView',$store->id)->with(['success'=>'Store create successful']);
    }

    public function view($id){
        $store=Store::select('stores.*','users.name as manager_name','shops.name as shop_name')
                    ->leftJoin('users','stores.manager_id','users.id')
                    ->leftJoin('shops','stores.shop_id','shops.id')
                    ->where('stores.id',$id)
                    ->first();
        $manager=User::where('position','manager')->get();
        $shop=Shop::where('active',1)->get();
        $currency=Currency::get();
        $supplier=Supplier::get();
        $product=Product::where('active',1)->get();
        $storeItem=StoreItem::select('store_items.*','products.name as product_name')
                            ->leftJoin('products','store_items.product_id','products.id')
                            ->where('store_id',$id)
                            ->get();
        return view('main.admin.store.view',compact('store','manager','shop','currency','storeItem','supplier','product'));
    }

    public function update(Request $req){
        $this->validation($req,'update');
        $data=$this->changeFormat($req,'update');
        Store::where('id',$req->id)->update($data);
        return back()->with(['success'=>'Store update successful.']);
    }

    public function delete(Request $req){
        $storeItem=StoreItem::where('store_id',$req->id)->get();
        $store=Store::where('id',$req->id)->first();
        if (empty($storeItem)) {
            Store::where('id',$req->id)->delete();
            return redirect()->route('admin#shopView',$store->shop_id)->with(['warning'=>'Store delete successful.']);
        }else{
            return back()->with(['danger'=>'In store delete, you must be remove store item first']);
        }
    }

    // private function
    private function validation($req,$status){
        Validator::make($req->all(),[
            'name'=>'required',
            'manager_id'=>'required',
            'shop_id'=>'required',
            'address'=>'required',
            'contact'=>'required',
            'active'=>$status=='create'?'':'required',
        ])->validate();
    }

    private function changeFormat($req,$status){
        return [
            'name'=>$req->name,
            'manager_id'=>$req->manager_id,
            'shop_id'=>$req->shop_id,
            'address'=>$req->address,
            'contact'=>$req->contact,
            'active'=>$status=='create'?0:$req->active,
        ];
    }
}
