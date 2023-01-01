<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Currencie;
use App\Models\Image;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades;


class ProductController extends Controller
{
    public function ProductPage(){
        $products = Product::with(['category','currencie'])->get();
        return view('admin/products/all',compact('products'));
    }

    public function AddNewProductPage(){
        $sellers = Seller::select(['id','name'])->where('status',1)->get();
        $categorys  = Category::select(['id','name'])->where('status',1)->get();
        $currencies = Currencie::where('status',1)->get();
        return view('admin/products/add',compact(['sellers','categorys','currencies']));
    }

    public function AddNewProduct(Request $request){
        $request->validate([
            'name' => 'required|max:100',
            'title' => 'required|max:255',
            'seller_id' =>'required|exists:sellers,id',
            'category_id' =>'required|exists:categorys,id',
            'currencie_id' =>'required',
            'quantity' =>'required|numeric|gt:0',
            'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg',
            'price' =>'required|numeric|gt:0',
        ]);

//        $photo_path = $request->photo->hashName();
//        $request->photo->move('.\products_photo',$photo_path);
        $photo_path = $request->file('photo')->store('products','Images');

        $product= Product::create([
            'name'=>$request->name,
            'title'=>$request->title,
            'seller_id'=>$request->seller_id,
            'category_id'=>$request->category_id,
            'currencie_id' =>$request->currencie_id,
            'quantity'=>$request->quantity,
            'photo'=>$photo_path,
            'price'=>$request->price,
            'status'=> $request->status ?? '0'
        ]);

        Image::create(['path'=>$photo_path,'imageable_id'=> $product->id , 'imageable_type'=>Product::class]);
        return redirect()->route('admin.products');
    }

    public function UpdateProductPage($id){
        $product = Product::findOrFail($id);
        $sellers = Seller::all();
        $categorys  = Category::where('status',1)->get();
        $currencies = Currencie::where('status',1)->get();
        return view('admin/products/update',compact(['product','sellers','categorys','currencies']));
    }

    public function UpdateProduct(Request $request){
        $request->validate([
            'name' => 'required|max:100',
            'title' => 'required|max:255',
            'seller_id' =>'required|exists:sellers,id',
            'category_id' =>'required|exists:categorys,id',
            'currencie_id' =>'required',
            'quantity' =>'required|numeric|gt:0',
            'photo' =>'image|mimes:jpeg,png,jpg,gif,svg',
            'price' =>'required|numeric|gt:0',
        ]);
        $product = Product::findOrFail($request->id);
        $data = [
            'name'=>$request->name,
            'title'=>$request->title,
            'seller_id'=>$request->seller_id,
            'category_id' =>$request->category_id,
            'currencie_id' =>$request->currencie_id,
            'quantity'=>$request->quantity,
            'price'=>$request->price,
            'status'=> $request->status ?? '0'
        ];
        if($request->photo){
            Facades\File::delete('storage/images/'.$product->photo);
//            $photo_path = $request->photo->hashName();
//            $request->photo->move('.\products_photo',$photo_path);
            $photo_path = $request->file('photo')->store('products','Images');
            $product->image->path = $photo_path;
            $product->image->save();
            $data += ['photo'=>$photo_path];
        }
        $product->update($data);
        return redirect()->route('admin.products')->with(['update'=>'ok']);
    }

    public function DeleteProduct(Request $request){
        $Product = Product::findOrFail($request->id);
        $id = $Product->id;
        $Product->image->delete();
//        Facades\Storage::disk('Images')->delete('/'.$Product->photo);
        $Product->delete();

        return response()->json(['status'=>true,'id'=>$id],200);
    }
}
