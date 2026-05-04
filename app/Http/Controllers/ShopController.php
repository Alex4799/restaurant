<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Models\Store;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function list(){
        $shop=Shop::when(request('search_key'),function($query){
                        $query->where('name','like','%'.request('search_key').'%');
                    })->get();
        return view('main.admin.shop.list',compact('shop'));
    }

    public function create(Request $req){
        $this->validation($req,'create');
        $data=$this->changeFormat($req,'create');
        if ($req->hasFile('image')) {
            $imageName=uniqid().$req->file('image')->getClientOriginalName();
            $req->file('image')->storeAs('public/shop',$imageName);
            $data['image']=$imageName;
        }
        Shop::create($data);
        return back()->with(['success'=>'Shop create successful.']);
    }

    public function view($id){
        $shop=Shop::where('id',$id)->first();
        $table=Table::where('shop_id',$id)->get();
        $store=Store::select('stores.*','users.name as manager_name')
                    ->leftJoin('users','stores.manager_id','users.id')
                    ->where('shop_id',$id)
                    ->get();
        $manager=User::where('position','manager')->get();
        return view('main.admin.shop.view',compact('shop','table','store','manager'));
    }

    public function update(Request $req){
        $this->validation($req,'update');
        $data=$this->changeFormat($req,'update');
        if ($req->hasFile('image')) {
            $oldImage=Shop::where('id',$req->id)->first()->image;
            Storage::delete('public/shop/'.$oldImage);
            $imageName=uniqid().$req->file('image')->getClientOriginalName();
            $req->file('image')->storeAs('public/shop',$imageName);
            $data['image']=$imageName;
        }
        Shop::where('id',$req->id)->update($data);
        return back()->with(['success'=>'Shop update successful.']);
    }

    public function delete(Request $req){
        $table=Table::where('id',$req->id)->get();
        if (count($table)==0) {
            $oldImage=Shop::where('id',$req->id)->first()->image;
            Storage::delete('public/shop/'.$oldImage);
            Shop::where('id',$req->id)->delete();
            return redirect()->route('admin#shopList')->with(['warning'=>'Shop delete successful.']);
        }else{
            return back()->with(['warning'=>'Need to delete the table of this shop first.']);
        }
    }

    // private function
    private function validation($req,$status){
        Validator::make($req->all(),[
            'name'=>'required',
            'image'=>$status=='create'?'required':'',
            'location'=>'required',
            'contact'=>'required',
            'active'=>$status=='create'?'':'required',
        ])->validate();
    }

    private function changeFormat($req,$status){
        return [
            'name'=>$req->name,
            'location'=>$req->location,
            'contact'=>$req->contact,
            'active'=>$status=='create'?1:$req->active,
        ];
    }
}
