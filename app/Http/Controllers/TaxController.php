<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxController extends Controller
{
    public function list(){
        $tax=Tax::when(request('search_key'),function($query){
                    $query->where('name','like','%'.request('search_key').'%');
                })->get();
        return view('main.admin.tax.list',compact('tax'));
    }

    public function create(Request $req){
        $this->validation($req,'create');
        $data=$this->changeFormat($req,'create');
        Tax::create($data);
        return back()->with(['success'=>'Tax create successful.']);
    }

    public function update(Request $req){
        $this->validation($req,'update');
        $data=$this->changeFormat($req,'update');
        Tax::where('id',$req->id)->update($data);
        return back()->with(['success'=>'Tax update successful']);
    }

    public function delete(Request $req){
        Tax::where('id',$req->id)->delete();
        return back()->with(['warning'=>'Tax delete successful.']);
    }

    public function check(Request $req){
        $tax=Tax::where('id',$req->id)->first();
        return response()->json($tax, 200);
    }

    // private function
    private function validation($req,$status){
        Validator::make($req->all(),[
            'name'=>'required',
            'default'=>$status=='create'?'':'required',
            'active'=>$status=='create'?'':'required',
        ])->validate();
    }

    private function changeFormat($req,$status){
        return [
            'name'=>$req->name,
            'percentage'=>$req->percentage,
            'amount'=>$req->amount,
            'default'=>$status=='create'?0:$req->default,
            'active'=>$status=='create'?0:$req->active,
        ];
    }
}
