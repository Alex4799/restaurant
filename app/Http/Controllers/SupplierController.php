<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function list(){
        $supplier=Supplier::when(request('search_key'),function($query){
                            $query->where('name','like','%'.request('search_key').'%');
                        })->get();
        return view('main.admin.supplier.list',compact('supplier'));
    }

    public function create(Request $req){
        $this->validation($req);
        $data=$this->changeFormat($req);
        Supplier::create($data);
        return back()->with(['success'=>'Supplier create successful.']);
    }

    public function update(Request $req){
        $this->validation($req);
        $data=$this->changeFormat($req);
        Supplier::where('id',$req->id)->update($data);
        return back()->with(['success'=>'Supplier update successful.']);
    }

    public function delete(Request $req){
        Supplier::where('id',$req->id)->delete();
        return back()->with(['warning'=>'Supplier delete successful.']);
    }

    private function validation($req){
        Validator::make($req->all(),[
            'name'=>'required',
            'contact'=>'required',
            'address'=>'required',
        ])->validate();
    }

    private function changeFormat($req){
        return [
            'name'=>$req->name,
            'contact'=>$req->contact,
            'address'=>$req->address,
        ];
    }
}
