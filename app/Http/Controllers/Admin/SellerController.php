<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{
    public function SellerPage(){
        $sellers = Seller::with(['product'])->get();
        return view('admin/sellers/all',compact('sellers'));
    }

    public function AddNewSellerPage(){
        return view('admin/sellers/add');
    }
    public function AddNewSeller(Request $request){
        $request->validate([
            'name' => 'required|max:100|unique:sellers,name',
            'email' => 'required|email|max:100|unique:sellers,email',
            'password' =>'required|string|min:6',
            'password_confirm' => 'required|same:password',
        ]);
        Seller::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'status'=>($request->status ?? '0')
        ]);
        return redirect()->route('admin.sellers');
    }

    public function UpdateSellerPage($id){
        $seller = Seller::findOrFail($id);
        return view('admin/sellers/update',compact('seller'));
    }
    public function UpdateSeller(Request $request){
        $seller = Seller::findOrFail($request->id);
        $rules = [];
        if ($request->name != $seller->name){
            $rules +=['name' => 'required|max:100|unique:sellers,name'];
        }
        if ($request->email != $seller->email){
            $rules +=['email' => 'required|max:100|unique:sellers,email'];
        }
        $request->validate($rules);
        $seller->name = $request->name;
        $seller->email = $request->email;
        $seller->status = $request->status ?? '0';
        $seller->save();
        return redirect()->route('admin.sellers')->with(['update'=>'ok']);
    }
    public function DeleteSeller(Request $request){
        $seller = Seller::findOrFail($request->id);
        $id = $seller->id;
        Product::where('seller_id',$id)->delete();
        $seller->delete();
        return response()->json(['status'=>true,'id'=>$id],200);
    }
}
