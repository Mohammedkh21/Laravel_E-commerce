<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function LoginPage(){
        return auth()->guard('seller')->check() ? redirect()->route('seller.mainPage') :  view('seller/login');
    }

    public function SellerLogin(Request $request){
        if( Auth::guard('seller')->attempt( $request->only(['email','password']) ) ){
            return redirect()->route('seller.mainPage');
        }
        return redirect()->back()->withErrors(['error'=>'email or password false']);
    }

    public function SellerLogout(){
        auth()->guard('admin')->logout();
        return redirect(RouteServiceProvider::HOME);
    }

    public function DashboardPage(){
        $products = Product::with('category')->where(['seller_id'=>auth()->guard('seller')->id()])->get();
        return view('seller/main',compact(['products']));
    }

}
