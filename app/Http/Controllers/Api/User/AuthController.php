<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;

class AuthController extends Controller
{
    public function Login(Request $request){
        if( $t= Auth::guard('web')->attempt( $request->only(['email','password']) ) ){
            $user = User::where('email',$request->email)->first();
            return response()->json([
                'status'=>'true',
                't '=>$t,
                'token'=>$user->createToken('auth_token')->plainTextToken
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
