<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function list(){
        $promotion=Promotion::select('promotions.*','shops.name as shop_name')
                            ->when(request('search_key'),function($query){
                                $query->where('promotions.name','like','%'.request('search_key').'%');
                            })
                            ->leftJoin('shops','promotions.shop_id','shops.id')
                            ->get();
        $shop=Shop::where('active',1)->get();
        return view('main.admin.promotion.list',compact('promotion','shop'));
    }

    public function create(Request $req){
        $this->validation($req,'create');
        $data=$this->changeFormat($req,'create');
        if ($req->hasFile('image')) {
            $imageName=uniqid().$req->file('image')->getClientOriginalName();
            $req->file('image')->storeAs('public/promotion',$imageName);
            $data['image']=$imageName;
        }
        $promotion=Promotion::create($data);
        return redirect()->route('admin#promotionView',$promotion->id)->with(['success'=>'Promotion create successfil.']);
    }

    public function view($id){
        $promotion=Promotion::select('promotions.*','shops.name as shop_name')
                            ->leftJoin('shops','promotions.shop_id','shops.id')
                            ->where('promotions.id',$id)
                            ->first();
        $shop=Shop::where('active',1)->get();
        return view('main.admin.promotion.view',compact('promotion','shop'));
    }

    public function update(Request $req){
        $this->validation($req,'update');
        $data=$this->changeFormat($req,'update');
        if ($req->hasFile('image')) {
            $oldImage=Promotion::where('id',$req->id)->first()->image;
            if ($oldImage!=null) {
                Storage::delete('public/promotion/'.$oldImage);
            }
            $imageName=uniqid().$req->file('image')->getClientOriginalName();
            $req->file('image')->storeAs('public/promotion',$imageName);
            $data['image']=$imageName;
        }
        Promotion::where('id',$req->id)->update($data);
        return back()->with(['success'=>'Promotion update successful.']);
    }

    public function delete(Request $req){
        $oldImage=Promotion::where('id',$req->id)->first()->image;
        if ($oldImage!=null) {
            Storage::delete('public/promotion/'.$oldImage);
        }
        Promotion::where('id',$req->id)->delete();
        return redirect()->route('admin#promotionList')->with(['warning'=>'Promotion delete successful.']);
    }

    public function check(Request $req){
        $promotion=Promotion::where('id',$req->id)->first();
        return response()->json($promotion, 200);
    }

    // private function
    private function validation($req,$status){
        Validator::make($req->all(),[
            'name'=>'required',
            'description'=>'required',
            'promo_code'=>'required',
            'image'=>$status=='create'?'required':'',
            'start_date'=>'required',
            'active'=>$status=='create'?'':'required',
            'feature'=>$status=='create'?'':'required',
        ])->validate();
    }

    private function changeFormat($req,$status){
        return [
            'name'=>$req->name,
            'description'=>$req->description,
            'promo_code'=>$req->promo_code,
            'shop_id'=>$req->shop_id,
            'percentage'=>$req->percentage,
            'amount'=>$req->amount,
            'start_date'=>$req->start_date,
            'end_date'=>$req->end_date,
            'limit'=>$req->limit,
            'active'=>$status=="create"?1:$req->active,
            'default'=>$status=="create"?0:$req->default,
            'feature'=>$status=="create"?0:$req->feature,
        ];
    }
}
