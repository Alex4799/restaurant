<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function list(){
        $user=User::where('role','admin')->get();
        $shop=Shop::where('active',0)->get();
        return view('main.admin.account.list',compact('user','shop'));
    }

    public function create(Request $req){
        $this->validation($req,'create');
        $data=$this->changeFormat($req);
        $data['password']=Hash::make($req->password);
        User::create($data);
        return back()->with(['success'=>'User create successful.']);
    }

    public function update(Request $req){
        $this->validation($req,'update');
        $data=$this->changeFormat($req);
        User::where('id',$req->id)->update($data);
        return back()->with(['success'=>'User update successful.']);
    }

    public function delete(Request $req){
        User::where('id',$req->id)->delete();
        return back()->with(['danger'=>'User delete successful.']);
    }

    // private function
    private function validation($req,$status){
        Validator::make($req->all(),[
            'name'=>'required',
            'email'=>'required|unique:users,email,'.$req->id,
            'phone'=>'required|unique:users,phone,'.$req->id,
            'address'=>'required',
            'position'=>'required',
            'password'=>$status=='create'?'required|min:6|max:15':'',
        ])->validate();
    }

    private function changeFormat($req){
        return [
            'name'=>$req->name,
            'email'=>$req->email,
            'phone'=>$req->phone,
            'address'=>$req->address,
            'position'=>$req->position,
            'shop'=>$req->shop,
            'role'=>'admin',
        ];
    }
}
