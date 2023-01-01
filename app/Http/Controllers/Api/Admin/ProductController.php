<?php

namespace App\Http\Controllers\Api\Admin;

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
    public function Products(){
        $products = Product::with(['category','currencie'])->get();
        return response()->json([
            'status'=>'true',
            'admins'=>$products
        ]);
    }

    public function Product(Request $request){
        $products = Product::with(['category','currencie'])->findOrFail($request->id);
        return response()->json([
            'status'=>'true',
            'admins'=>$products
        ]);
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
        return response()->json([
            'status'=>'true',
            'admins'=>$product
        ]);
    }

    public function DeleteProduct(Request $request){
        $Product = Product::findOrFail($request->id);
        $Product->image->delete();
//        Facades\Storage::disk('Images')->delete('/'.$Product->photo);
        $Product->delete();

        return response()->json([
            'status'=>'true',
            'admins'=>$Product
        ]);
    }
}
