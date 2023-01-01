<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Currencie;
use App\Models\Image;
use App\Models\Product;
use App\Models\Seller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades;


class SellerController extends Controller
{
    public  function  LoginPage(){
        if (Auth::guard('seller')->check()){
            return redirect()->route('seller.mainPage');
        }
        return view('seller/login');
    }
    public function login(Request $request){
        if(Auth::guard('seller')->attempt($request->only(['email','password']))){
            return redirect()->route('seller.mainPage');
        }
        return  redirect()->back()->withErrors(['error'=>'email or password not correct']);
    }
    public function logout(){
        Auth::guard('seller')->logout();
        return redirect(RouteServiceProvider::HOME);
    }
    public function mainPage(){
        $products = Product::with('category')->where(['seller_id'=>'9'])->get();
        return view('seller/main',compact(['products']));
    }
    public function addProductPage(){
        $categorys  = Category::select(['id','name'])->get();
        $currencies = Currencie::where('status',1)->get();
        return view('seller/addProduct',compact(['categorys','currencies']));
    }
    public function addProduct(Request $request){
        $cc =  Category::pluck('id');
        $c_id='';
        foreach ($cc as $c){
            $c_id .=$c.',';
        }
        $request->validate([
            'name' => 'required|max:100',
            'title' => 'required|max:255',
            'quantity' =>'required|numeric|gt:0',
            'category_id' =>'required|in:'.$c_id,
            'currencie_id' =>'required',
            'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg',
            'price' =>'required|numeric|gt:0',
        ]);
        $photo_path = $request->photo->hashName();
        $request->photo->move('.\products_photo',$photo_path);
        $p= Product::create([
            'name'=>$request->name,
            'title'=>$request->title,
            'seller_id'=> Auth::guard('seller')->id(),
            'category_id'=>$request->category_id,
            'quantity'=>$request->quantity,
            'currencie_id' =>$request->currencie_id,
            'photo'=>$photo_path,
            'price'=>$request->price,
            'status'=> $request->status ?? '0'
        ]);

        Image::create(['path'=>$photo_path,'imageable_id'=> $p->id , 'imageable_type'=>Product::class]);
        return redirect()->route('seller.mainPage');
    }
    public function updateProducts($id){
        $product = Product::find($id);
        $categorys  = Category::select(['id','name'])->get();
        $currencies = Currencie::where('status',1)->get();
        if ($product){
            return view('seller/updateProduct',compact(['product','categorys','currencies']));
        }
        return redirect()->route('seller.mainPage')->withErrors(['error'=>'Product not found']);
    }
    public function updateProduct(Request $request){
        $cc =  Category::pluck('id');
        $c_id='';
        foreach ($cc as $c){
            $c_id .=$c.',';
        }
        $request->validate([
            'name' => 'required|max:100',
            'title' => 'required|max:255',
            'quantity' =>'required|numeric|gt:0',
            'category_id' =>'required|in:'.$c_id,
            'currencie_id' =>'required',
            'photo' =>'image|mimes:jpeg,png,jpg,gif,svg',
            'price' =>'required|numeric|gt:0',
        ]);
        $product = Product::find($request->id);
        $data = [
            'name'=>$request->name,
            'title'=>$request->title,
            'seller_id'=> Auth::guard('seller')->id(),
            'category_id' =>$request->category_id,
            'currencie_id' =>$request->currencie_id,
            'quantity'=>$request->quantity,
            'price'=>$request->price,
            'status'=> $request->status ?? '0'
        ];
        if($request->photo){
            Facades\File::delete('./products_photo/'.$product->photo);
            $photo_path = $request->photo->hashName();
            $request->photo->move('.\products_photo',$photo_path);
            $product->image->path = $photo_path;
            $product->image->save();
            $data += ['photo'=>$photo_path];
        }
        Product::where('id',$request->id)->update($data);
        return redirect()->route('seller.mainPage')->with(['update'=>'ok']);
    }
    public function deleteProduct(Request $request){
        $Product = Product::find($request->id);
        if($Product){
            $id = $Product->id;
            $Product->image->delete();
            Facades\File::delete('./products_photo/'.$Product->photo);
            $Product->delete();
            return response()->json(['status'=>true,'id'=>$id],200);
        }
        return response()->json(['status'=>false],404);
    }
}
