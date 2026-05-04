<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function list(){
        $payment=Payment::select('payments.*','shops.name as shop_name')
                        ->when(request('search_key'),function($query){
                            $query->where('payments.name','like','%'.request('search_key').'%');
                        })
                        ->leftJoin('shops','payments.shop_id','shops.id')
                        ->get();
        $shop=Shop::where('active',1)->get();
        return view('main.admin.payment.list',compact('payment','shop'));
    }

    public function create(Request $req){
        $this->validation($req,'create');
        $data=$this->changeFormat($req,'create');
        if ($req->hasFile('image')) {
            $imageName=uniqid().$req->file('image')->getClientOriginalName();
            $req->file('image')->storeAs('public/qr',$imageName);
            $data['qr']=$imageName;
        }
        Payment::create($data);
        return back()->with(['success'=>'Payment create successful.']);
    }

    public function update(Request $req){
        $this->validation($req,'update');
        $data=$this->changeFormat($req,'update');
        if ($req->hasFile('image')) {
            $oldImage=Payment::where('id',$req->id)->first()->qr;
            if ($oldImage!=null) {
                Storage::delete('public/qr/'.$oldImage);
            }
            $imageName=uniqid().$req->file('image')->getClientOriginalName();
            $req->file('image')->storeAs('public/qr',$imageName);
            $data['qr']=$imageName;
        }
        Payment::where('id',$req->id)->update($data);
        return back()->with(['success'=>'Payment update successful.']);
    }

    public function delete(Request $req){
        $oldImage=Payment::where('id',$req->id)->first()->qr;
        if ($oldImage!=null) {
            Storage::delete('public/qr/'.$oldImage);
        }
        Payment::where('id',$req->id)->delete();
        return back()->with(['warning'=>'Payment delete successful.']);
    }

    // private function
    private function validation($req,$status){
        Validator::make($req->all(),[
            'name'=>'required',
            'user_name'=>'required',
            'number'=>'required',
            'active'=>$status=='create'?'':'required',
        ])->validate();
    }

    private function changeFormat($req,$status){
        return [
            'name'=>$req->name,
            'user_name'=>$req->user_name,
            'number'=>$req->number,
            'name'=>$req->name,
            'shop_id'=>$req->shop_id,
            'active'=>$status=='create'?1:$req->active,
        ];
    }
}
