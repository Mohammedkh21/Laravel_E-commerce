<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function CategoryPage(){
        $categorys = Category::all();
        return view('admin/category/all',compact('categorys'));
    }

    public function AddNewCategoryPage(){
        return view('admin/category/add');
    }

    public function AddNewCategory(Request $request){
        $request->validate(['name'=>'required|unique:categorys,name']);
        Category::create(['name'=>$request->name , 'status'=> $request->status ?? 0]);
        return redirect()->route('admin.categorys');
    }

    public  function  UpdateCategoryPage($id){
        $category = Category::findOrFail($id);
        return view('admin/category/update',compact('category'));
    }

    public  function UpdateCategory(Request $request){
        $category = Category::with('product')->find($request->id);
        $rules = [];
        if ($request->name != $category->name){
            $rules +=['name' => 'required|max:100|unique:currencies,name'];
        }
        if(! $request->status  ){
            foreach ($category->product as $product){
                $product->status = '0';
                $product->save();
            }
        }
        $request->validate($rules);
        $category->name = $request->name;
        $category->status = $request->status ?? '0';
        $category->save();
        return redirect()->route('admin.categorys')->with(['update'=>'update done']);
    }

    public  function DeleteCategory(Request $request){
        $category = Category::with('product')->findOrFail($request->id);

        $id = $category->id;
        $category->delete();
        return response()->json(['status'=>true,'id'=>$id],200);

    }
}
