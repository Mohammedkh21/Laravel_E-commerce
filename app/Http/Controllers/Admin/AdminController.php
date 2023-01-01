<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminPage(){
        $admins = Admin::whereRaw('owner = ? and id != ? ',[0,auth()->guard('admin')->id()])->get();
        foreach ($admins as $admin){
            $admin->permissions = json_decode($admin->permissions);
        }
        return view('admin/admins/all',compact('admins'));
    }

    public function AddNewAdminPage(){
        return view('admin/admins/add');
    }

    public function AddNewAdmin(Request $request){
        $request->validate([
            'name' => 'required|max:100|unique:sellers,name',
            'email' => 'required|email|max:255|unique:sellers,email',
            'password' =>'required|string|min:6',
            'password_confirm' => 'required|same:password',
        ]);
        Admin::create([
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
        return redirect()->route('admin.admins');
    }

    public function UpdateAdminPage($id){
        $admin = Admin::findOrFail($id);
        return view('admin/admins/update',compact('admin'));
    }

    public function UpdateAdmin(Request $request){
        $admin= Admin::find($request->id);
        $rules = [];
        if ($admin->name != $request->name){
            $rules += ['name' => 'required|string|max:100|unique:admins,name'];
        }
        if ($admin->email != $request->email){
            $rules += ['email' => 'required|email|max:100|unique:admins,email'];
        }
        $request->validate($rules);
        Admin::where('id', $request->id)->update(['name'=>$request->name,'email'=>$request->email ]);

        return redirect()->route('admin.admins')->with(['update'=>'update done']);
    }

    public function DeleteAdmin(Request $request){
        $admin = Admin::findOrFail($request->id);
        $id = $admin->id;
        $admin->delete();
        return response()->json(['status'=>true,'id'=>$id],200);
    }

    public  function UpdateAdminPermissions(Request $request){

        $admin = Admin::findOrFail($request->id);
        $permissions = json_decode($admin->permissions);
        $indix = array_search($request->category,array_column($permissions,'0')) ;
        $opartion = $request->opartion;
        $permissions[$indix]->$opartion =intval( $request->status);
        $admin->permissions = json_encode($permissions);
        $admin->save();
        return response()->json(['status'=>true,'permissions'=>$permissions]);
    }




}
