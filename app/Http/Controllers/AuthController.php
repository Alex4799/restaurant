<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function dashboard(Request $request){
        if (Auth::user()->role=='admin') {
            return redirect()->route('admin#profile');
        }else{
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/');
        }
    }

    public function profile(){
        return view('main.admin.account.profile');
    }

    public function profileUpdate(Request $req){
        Validator::make($req->all(),[
            'name'=>'required',
            'email'=>'required|unique:users,email,'.Auth::user()->id,
            'phone'=>'required',
            'address'=>'required',
        ])->validate();
        $data=[
            'name'=>$req->name,
            'email'=>$req->email,
            'phone'=>$req->phone,
            'address'=>$req->address,
        ];
        User::where('id',Auth::user()->id)->update($data);
        return back()->with(['success'=>'Profile update successful.']);
    }

    public function changePassword(Request $req){
        Validator::make($req->all(),[
            'currentPassword'=>'required',
            'newPassword'=>'required|min:6|max:15',
            'confirmPassword'=>'required|same:newPassword',
        ])->validate();
        if(Hash::check($req->currentPassword, Auth::user()->password)){
            User::where('id',Auth::user()->id)->update(['password'=>Hash::make($req->newPassword)]);
            return back()->with(['success'=>'Password update successful.']);
        }else{
            return back()->with(['warning'=>'Current Password wrong. Please try again.']);
        }
    }
}
