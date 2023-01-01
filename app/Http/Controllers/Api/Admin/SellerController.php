<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{
    public function Sellers(){
        $sellers = Seller::with(['product'])->get();
        return response()->json([
            'status'=>'true',
            'admins'=>$sellers
        ]);
    }
    public function Seller(Request $request){
        $seller = Seller::with(['product'])->findOrFail($request->id);
        return response()->json([
            'status'=>'true',
            'admins'=>$seller
        ]);
    }

    public function AddNewSeller(Request $request){
        $request->validate([
            'name' => 'required|max:100|unique:sellers,name',
            'email' => 'required|email|max:100|unique:sellers,email',
            'password' =>'required|string|min:6',
            'password_confirm' => 'required|same:password',
        ]);
        $seller = Seller::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'status'=>($request->status ?? '0')
        ]);
        return response()->json([
            'status'=>'true',
            'admins'=>$seller
        ]);
    }

    public function DeleteSeller(Request $request){
        $seller = Seller::findOrFail($request->id);
        $seller->delete();
        return response()->json([
            'status'=>'true',
            'admins'=>$seller
        ]);
    }
}
