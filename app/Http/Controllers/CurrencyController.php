<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    public function list(){
        $currency=Currency::when(request('search_key'),function($query){
                                $query->where('name','like','%'.request('search_key').'%');
                            })->get();
        return view('main.admin.currency.list',compact('currency'));
    }

    public function create(Request $req){
        $this->validation($req);
        $data=$this->changeFormat($req);
        Currency::create($data);
        return back()->with(['success'=>'Currency create successful.']);
    }

    public function update(Request $req){
        $this->validation($req);
        $data=$this->changeFormat($req);
        Currency::where('id',$req->id)->update($data);
        return back()->with(['success'=>'Currency update successful.']);
    }

    public function delete(Request $req){
        Currency::where('id',$req->id)->delete();
        return back()->with(['warning'=>'Currency delete successful.']);
    }

    // private function
    private function validation($req){
        Validator::make($req->all(),[
            'name'=>'required',
            'currency_code'=>'required',
        ])->validate();
    }

    public function changeFormat($req){
        return [
            'name'=>$req->name,
            'currency_code'=>$req->currency_code,
        ];
    }
}
