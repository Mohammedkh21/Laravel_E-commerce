<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function Admins(Request $request){
        $admins = Admin::whereRaw('owner = ? and id != ? ',[0,auth()->id() ])->get();
        foreach ($admins as $admin){
            $admin->permissions = json_decode($admin->permissions);
        }
        return response()->json([
            'status'=>'true',
            'admins'=>$admins->onlyFil
        ]);
    }

    public function Admin(Request $request){
        $request->validate(['id' => 'required|exists:admins,id']);
        $admin = Admin::find($request->id);
        $admin->permissions = json_decode($admin->permissions);
        return response()->json([
            'status'=>'true',
            'admins'=>$admin
        ]);
    }

    public function AddNewAdmin(Request $request){
        $request->validate([
            'name' => 'required|max:100|unique:sellers,name',
            'email' => 'required|email|max:255|unique:sellers,email',
            'password' =>'required|string|min:6',
            'password_confirm' => 'required|same:password',
        ]);
        $admin= Admin::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)  ,
            'permissions'=> json_encode( [
                ['admin','v'=>0,'a'=>0,'u'=>0,'d'=>0],
                ['category','v'=>0,'a'=>0,'u'=>0,'d'=>0],
                ['product','v'=>0,'a'=>0,'u'=>0,'d'=>0],
                ['seller','v'=>0,'a'=>0,'u'=>0,'d'=>0],
                ['order','v'=>0,'a'=>0,'u'=>0,'d'=>0],
                ['currencie','v'=>0,'a'=>0,'u'=>0,'d'=>0]
            ])
        ]);
        $admin->permissions = json_decode($admin->permissions);
        return response()->json([
            'status'=>'true',
            'admins'=>$admin
        ]);
    }

    public function DeleteAdmin(Request $request){
        $admin = Admin::findOrFail($request->id);
        $admin->delete();
        return response()->json([
            'status'=>'true',
            'admins'=>$admin
        ]);
    }

}
