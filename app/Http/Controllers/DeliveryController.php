<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\WebsiteInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    public function list(){
        $delivery=Delivery::when(request('search_key'),function($query){
                    $query->where('township','like','%'.request('search_key').'%');
                })->get();
        $deliveryStatus=WebsiteInfo::where('id',1)->first()->delivery;
        return view('main.admin.delivery.list',compact('delivery','deliveryStatus'));
    }

    public function create(Request $req){
        $this->validation($req,'create');
        $data=$this->changeFormat($req,'creaet');
        Delivery::create($data);
        return back()->with(['success'=>'Delivery create successful.']);
    }

    public function update(Request $req){
        $this->validation($req,'update');
        $data=$this->changeFormat($req,'update');
        Delivery::where('id',$req->id)->update($data);
        return back()->with(['success'=>'Delivery update successful.']);
    }

    public function delete(Request $req){
        Delivery::where('id',$req->id)->delete();
        return back()->with(['warning'=>'Delivery delete successful.']);
    }

    public function changeStatus($status){
        WebsiteInfo::where('id',1)->update(['delivery'=>$status]);
        return back()->with(['success'=>'Change delivery status duccessful.']);
    }

    // private function
    private function validation($req,$status){
        Validator::make($req->all(),[
            'township'=>'required',
            'fees'=>'required',
            'active'=>$status=='create'?'':'required',
        ])->validate();
    }

    private function changeFormat($req,$status){
        return [
            'township'=>$req->township,
            'fees'=>$req->fees,
            'active'=>$status=='create'?1:$req->active,
        ];
    }
}
