<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function Categorys(){
        $categorys = Category::all();
        return response()->json([
            'status'=>'true',
            'categorys'=>$categorys
        ]);
    }

    public function Category(Request $request){
        $category = Category::find($request->id);
        return response()->json([
            'status'=>'true',
            'category'=>$category
        ]);
    }

    public function AddNewCategory(Request $request){
        $request->validate(['name'=>'required|unique:categorys,name']);
        $category = Category::create(['name'=>$request->name , 'status'=> $request->status ?? 0]);
        return response()->json([
            'status'=>'true',
            'category'=>$category
        ]);
    }

    public  function DeleteCategory(Request $request){
        $category = Category::with('product')->findOrFail($request->id);
        $category->delete();
        return response()->json([
            'status'=>'true',
            'category'=>$category
        ]);
    }
}
