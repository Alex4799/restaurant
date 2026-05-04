<?php

namespace App\Http\Controllers;

use App\Models\Reduce;
use App\Models\Product;
use App\Models\StoreItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReduceController extends Controller
{
    public function list(){
        $baseQuery=Reduce::select('reduces.*','users.name as user_name','stores.name as store_name')
                            ->leftJoin('users','reduces.reduce_by','users.id')
                            ->leftJoin('stores','reduces.store_id','stores.id')
                            ->when(request('search_key'),function($query){
                                $query->where('reduces.id',request('search_key'));
                            })
                            ->when(request('product'),function($query){
                                $query->where('reduces.name',request('product'));
                            })
                            ->when(request('start_date'),function($query){
                                $query->whereDate('reduces.created_at','>=',request('start_date'));
                            })
                            ->when(request('end_date'),function($query){
                                $query->whereDate('reduces.created_at','<=',request('end_date'));
                            });

        $normalReduce=(clone $baseQuery)
                    ->where('reduces.type',0)
                    ->orderBy('reduces.created_at','desc')
                    ->paginate(10);

        $normalReduceTotalPrice=(clone $baseQuery)->where('reduces.type',0)->sum('reduces.total_price');

        $damageReduce=(clone $baseQuery)
                    ->where('reduces.type',1)
                    ->orderBy('reduces.created_at','desc')
                    ->paginate(10);

        $damageReduceTotalPrice=(clone $baseQuery)->where('reduces.type',1)->sum('reduces.total_price');

        $product=Product::where('active',1)->get();

        return view('main.admin.reduce.list',compact('normalReduce','damageReduce','normalReduceTotalPrice','damageReduceTotalPrice','product'));
    }

    public function create(Request $req){
        $this->validation($req);
        $storeItem=StoreItem::select('store_items.*','products.name as product_name')
                        ->leftJoin('products','store_items.product_id','products.id')
                        ->where('store_items.id',$req->store_item_id)
                        ->first();
        if ($storeItem->qty>=$req->qty || $storeItem->instock_type==1) {
            $data=[
                'store_item_id'=>$req->store_item_id,
                'product_name'=>$storeItem->product_name,
                'store_id'=>$storeItem->store_id,
                'qty'=>$req->qty,
                'total_price'=>$req->qty*$storeItem->purchase_price,
                'type'=>$req->type,
                'reduce_by'=>Auth::user()->id,
            ];
            if ($storeItem->instock_type==0) {
                StoreItem::where('id',$storeItem->id)->decrement('qty',$req->qty);
            }
            Reduce::create($data);
            $message=['success'=>'Reduce create successful'];
        }else{
            $message=['danger'=>"Max qty of store item is $storeItem->qty"];
        }
        return back()->with($message);
    }

    public function delete(Request $req){
        $reduce=Reduce::findOrFail($req->id);
        $storeItem=StoreItem::findOrFail($reduce->store_item_id);
        if ($storeItem->instock_type==0) {
            $storeItem->increment('qty',$reduce->qty);
        }
        $reduce->delete();
        return back()->with(['warning'=>'Reduce delete successful.']);
    }

    private function validation($req){
        Validator::make($req->all(),[
            'store_item_id'=>'required',
            'qty'=>'required',
            'type'=>'required',
        ])->validate();
    }
}
