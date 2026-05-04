<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function list(){
        $category=Category::when(request('search_key'),function($query){
                        $query->where('name','like','%'.request('search_key').'%');
                    })->orderBy('created_at','desc')->get();
        return view('main.admin.category.list',compact('category'));
    }

    public function create(Request $req){
        $this->validation($req);
        $data=$this->changeFormat($req);
        Category::create($data);
        return back()->with(['success'=>'Category create successful']);
    }

    public function update(Request $req){
        $this->validation($req);
        $data=$this->changeFormat($req);
        Category::where('id',$req->id)->update($data);
        return back()->with(['success'=>'Category update successful']);
    }

    public function delete(Request $req){
        Product::where('category_id',$req->id)->update(['category_id'=>1]);
        Category::where('id',$req->id)->delete();
        return back()->with(['success'=>'Category delete successful.']);
    }

    // private function
    private function validation($req){
        Validator::make($req->all(),[
            'name'=>'required',
        ])->validate();
    }

    private function changeFormat($req){
        return [
            'name'=>$req->name,
        ];
    }
}
