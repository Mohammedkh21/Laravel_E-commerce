<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //

    public function Login(Request $request){
        if( Auth::guard('admin')->attempt( $request->only(['email','password']) ) ){
            $admin = Admin::where('email',$request->email)->first();
            return response()->json([
                'status'=>'true',
                'token'=>$admin->createToken('auth_token')->plainTextToken
            ]);
        }
        return response()->json(['error'=>'email or password false']);
    }

    public function Logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'status'=>'true',
        ]);
    }
}
