<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
{
    public function create(Request $req){
        $this->validation($req,'create');
        $data=$this->changeFormat($req,'create');
        Table::create($data);
        return back()->with(['success'=>'Table create successful.']);
    }

    public function update(Request $req){
        $this->validation($req,'update');
        $data=$this->changeFormat($req,'update');
        Table::where('id',$req->id)->update($data);
        return back()->with(['success'=>'Table update successful']);
    }

    public function delete(Request $req){
        Table::where('id',$req->id)->delete();
        return back()->with(['warning'=>'Table delete successful']);
    }

    // private function
    private function validation($req,$status){
        Validator::make($req->all(),[
            'name'=>'required',
            'capacity'=>'required',
            'status'=>$status=='create'?'':'required',
            'active'=>$status=='create'?'':'required',
        ])->validate();
    }

    private function changeFormat($req,$status){
        return [
            'name'=>$req->name,
            'capacity'=>$req->capacity,
            'shop_id'=>$req->shop_id,
            'status'=>$status=='create'?0:$req->status,
            'active'=>$status=='create'?1:$req->active,
        ];
    }
}
