<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function list(){
        $paginate=request('pagination')??25;
        $product=Product::select('products.*','categories.name as category_name')
                        ->when(request('search_key'),function($query){
                            $query->where('products.name','like','%'.request('search_key').'%');
                        })->when(request('category'),function($query){
                            $query->where('category_id',request('category'));
                        })->when(request('active'),function($query){
                            $query->where('products.active',request('active'));
                        })
                        ->leftJoin('categories','products.category_id','categories.id')
                        ->orderBy('products.created_at','desc')
                        ->paginate($paginate);
        $category=Category::get();
        return view('main.admin.product.list',compact('product','category','paginate'));
    }

    public function view($id){
        $product=Product::select('products.*','categories.name as category_name')
                        ->when(request('search_key'),function($query){
                            $query->where('name','like','%'.request('search_key').'%');
                        })
                        ->leftJoin('categories','products.category_id','categories.id')
                        ->where('products.id',$id)
                        ->first();

        $category=Category::get();
        return view('main.admin.product.view',compact('product','category'));
    }

    public function create(Request $req){
        $this->validation($req,'create');
        $data=$this->changeFormat($req,'create');
        if ($req->hasFile('image')) {
            $imageName=uniqid().$req->file('image')->getClientOriginalName();
            $req->file('image')->storeAs('public/product',$imageName);
            $data['image']=$imageName;
        }
        Product::create($data);
        return back()->with(['success'=>'Product create successful.']);
    }

    public function update(Request $req){
        $this->validation($req,'update');
        $data=$this->changeFormat($req,'update');
        if ($req->hasFile('image')) {
            $productImage=Product::where('id',$req->id)->first()->image;
            Storage::delete('public/product/'.$productImage);
            $imageName=uniqid().$req->file('image')->getClientOriginalName();
            $req->file('image')->storeAs('public/product',$imageName);
            $data['image']=$imageName;
        }
        Product::where('id',$req->id)->update($data);
        return back()->with(['success'=>'Product update successful.']);
    }

    public function delete(Request $req){
        $product=Product::where('id',$req->id)->first();
        Storage::delete('public/product/'.$product->image);
        Product::where('id',$req->id)->delete();
        return redirect()->route('admin#productList')->with(['warning'=>'Product delete successful.']);
    }

    // private function
    private function validation($req,$status){
        Validator::make($req->all(),[
            'name'=>'required',
            'description'=>'required',
            'image'=>$status=='create'?'required':'',
            'category'=>'required',
            'active'=>$status=='create'?'':'required',
        ])->validate();
    }

    private function changeFormat($req,$status){
        return [
            'name'=>$req->name,
            'description'=>$req->description,
            'category_id'=>$req->category,
            'default_image'=>$req->default_image,
            'active'=>$status=='create'?1:$req->active,
        ];
    }
}
