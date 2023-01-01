<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Currencie;
use App\Models\Image;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades;


class ProductController extends Controller
{

    public function AllProduct(Request $request){

        $products = Facades\Cache::remember('Products',5,function (){
            return Product::with('category')->where(['seller_id'=>auth()->id()])->get();
        });

        return response()->json([
            'status'=>'true',
            'products'=>$products
        ]);
    }
    public function Product(Request $request){
        $product = Product::with('category')->where(['seller_id'=>auth()->id()])->findOrFail($request->id);
        return response()->json([
            'status'=>'true',
            'product'=>$product
        ]);
    }

    public function AddNewProduct(Request $request){
        $request->validate([
            'name' => 'required|max:100',
            'title' => 'required|max:255',
            'quantity' =>'required|numeric|gt:0',
            'category_id' =>'required|exists:categorys,id',
            'currencie_id' =>'required|exists:currencies,id',
            'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg',
            'price' =>'required|numeric|gt:0',
        ]);
//        $photo_path = $request->photo->hashName();
//        $request->photo->move('.\products_photo',$photo_path);
        $photo_path = $request->file('photo')->store('products','Images');

        $product= Product::create([
            'name'=>$request->name,
            'title'=>$request->title,
            'seller_id'=> auth()->id(),
            'category_id'=>$request->category_id,
            'quantity'=>$request->quantity,
            'currencie_id' =>$request->currencie_id,
            'photo'=>$photo_path,
            'price'=>$request->price,
            'status'=> $request->status ?? '0'
        ]);

        Image::create(['path'=>$photo_path,'imageable_id'=> $product->id , 'imageable_type'=>Product::class]);
        return response()->json([
            'status'=>'true',
            'product'=>$product
        ]);
    }

    public function DeleteProduct(Request $request){
        $Product = Product::where(['seller_id'=>auth()->id()])->findOrFail($request->product_id);
        $Product->image->delete();
//        Facades\Storage::disk('Images')->delete('/'.$Product->photo);
        $Product->delete();
        return response()->json([
            'status'=>'true',
            'product'=> $Product,
        ]);
    }
}
