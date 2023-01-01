<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function LoginPage(){
        return auth()->guard('admin')->check() ? redirect()->route('admin.dashboard') :  view('admin/login');
    }

    public function AdminLogin(Request $request){
        if( Auth::guard('admin')->attempt( $request->only(['email','password']) ) ){

            return redirect()->route('admin.dashboard');

        }
        return redirect()->back()->withErrors(['error'=>'email or password false']);
    }

    public function LogoutAdmin(){
        auth()->guard('admin')->logout();
        return redirect(RouteServiceProvider::HOME);
    }

    public function DashboardPage(){
        return view('admin/main');
    }
}
