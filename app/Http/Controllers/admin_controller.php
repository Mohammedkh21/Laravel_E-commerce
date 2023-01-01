<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Currencie;
use App\Models\Image;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades;

class admin_controller extends Controller
{
    public function login_page(){
        return view('admin/login');
    }
    public function admin_login(Request $request){

        if( Auth::guard('admin')->attempt( $request->only(['email','password']) ) ){

            return redirect()->route('admin.dashboard');
        }
        return redirect()->back()->withErrors(['error'=>'not found']);
    }

    public function dashboard(){
        return view('admin/main');
    }












    public function admins(){ $this->authorize('viewAdmin',auth()->guard('admin')->user());
        $admins = Admin::where('owner',0)->get();
        foreach ($admins as $admin){
            $admin->permissions = json_decode($admin->permissions);
        }

        return view('admin/admins/all',compact('admins'));
    }
    public function admins_add(){ $this->authorize('addAdmin',auth()->guard('admin')->user());
        return view('admin/admins/add');
    }
    public function admins_add_new(Request $request){ $this->authorize('addAdmin',auth()->guard('admin')->user());
        $request->validate([
            'name' => 'required|max:100|unique:sellers,name',
            'email' => 'required|email|max:255|unique:sellers,email',
            'password' =>'required| min:6',
            'confirm_password' => 'required_with:password|same:password|min:6',
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



    public function admins_update($id){  $this->authorize('updateAdmin',auth()->guard('admin')->user());
        $admin = Admin::findOrFail($id);
        if ($admin){
            return view('admin/admins/update',compact('admin'));
        }
        return redirect()->route('admin.admins')->withErrors(['error'=>'seller not found']);
    }



    public function admin_update(Request $request){
        $roul = [
            'name' => 'required|max:100|unique:sellers,name',
            'email' => 'required|email|max:255|unique:sellers,email',
        ];
        $request->validate($roul);
        Admin::where('id', $request->id)->update(['updated_at'=>date_create(date('Y-m-d h:i:s')),'name'=>$request->name,'email'=>$request->email,'password'=>Hash::make($request->password) ]);

        return redirect()->route('admin.admins')->with(['update'=>'update done']);
    }
    public  function admin_permissions_update(Request $request){


        $this->authorize('updateAdmin',auth()->guard('admin')->user());



        $admin = Admin::find($request->id);
        $permissions = json_decode($admin->permissions);
        $indix = array_search($request->category,array_column($permissions,'0')) ;
        $opartion = $request->opartion;
        $permissions[$indix]->$opartion =intval( $request->status);
        $admin->permissions = json_encode($permissions);
        $admin->save();
        return response()->json(['status'=>true,'permissions'=>$permissions]);
    }
    public function admins_delete(Request $request){  $this->authorize('deleteAdmin',auth()->guard('admin')->user());

        $admin = Admin::find($request->id);
        if($admin){
            $id = $admin->id;
            $admin->delete();
            return response()->json(['status'=>true,'id'=>$id],200);
        }
        return response()->json(['status'=>false],404);
    }
    public function admin_logout(){
        \auth()->guard('admin')->logout();
        return redirect(RouteServiceProvider::HOME);
    }



    public function category(){ $this->authorize('viewCategory',auth()->guard('admin')->user());
        $categorys = Category::all();
        return view('admin/category/all',compact('categorys'));
    }
    public function addCategory(){ $this->authorize('addCategory',auth()->guard('admin')->user());
        return view('admin/category/add');
    }
    public function addCategoryNew(Request $request){ $this->authorize('addCategory',auth()->guard('admin')->user());
        $request->validate(['name'=>'required|unique:categorys,name']);
        Category::create(['name'=>$request->name , 'status'=> $request->status ?? 0]);
        return redirect()->route('admin.categorys');
    }
    public  function  updateCategorys($id){ $this->authorize('updateCategory',auth()->guard('admin')->user());
        $category = Category::find($id);
        if ($category){
            return view('admin/category/update',compact('category'));
        }
        return redirect()->back()->withErrors(['error'=>'seller not found']);
    }
    public  function updateCategory(Request $request){

        $this->authorize('updateCategory',auth()->guard('admin')->user());

        $category = Category::with('product')->find($request->id);
        $roul = [];
        if ($request->name != $category->name){
            $roul +=['name' => 'required|max:100|unique:sellers,name'];
        }
        if($request->status !=$category->status ){
            foreach ($category->product as $product){
                $product->status = $request->status ?? '0';
                $product->save();
            }
        }


        $request->validate($roul);
        Category::where('id', $request->id)->update(['name'=>$request->name,'status'=>$request->status ?? '0' ]);

        return redirect()->route('admin.categorys')->with(['update'=>'update done']);
    }
    public  function deleteCategory(Request $request){ $this->authorize('deleteCategory',auth()->guard('admin')->user());
        $category = Category::with('product')->find($request->id);
        if($category){
            $id = $category->id;
            foreach ($category->product as $product){
                $product->delete();
            }
            $category->delete();
            return response()->json(['status'=>true,'id'=>$id],200);
        }
        return response()->json(['status'=>false],404);
    }





    public function sellers(){ $this->authorize('viewSeller',auth()->guard('admin')->user());
        $sellers = Seller::with(['product'])->get();
        return view('admin/sellers/all',compact('sellers'));
    }
    public function sellers_add(){ $this->authorize('addSeller',auth()->guard('admin')->user());
        return view('admin/sellers/add');
    }
    public function sellers_add_new(Request $request){ $this->authorize('addSeller',auth()->guard('admin')->user());
            $request->validate([
                'name' => 'required|max:100|unique:sellers,name',
                'email' => 'required|email|max:255|unique:sellers,email',
                'password' =>'required',
                'confirm_password' => 'same:password',
            ]);
            Seller::create(['name'=>$request->name,'email'=>$request->email,'password'=>Hash::make($request->password),'status'=>($request->status ?? '0')  ]);
            return redirect()->route('admin.sellers');
    }

    public function sellers_update($id){ $this->authorize('updateSeller',auth()->guard('admin')->user());
        $seller = Seller::find($id);
        if ($seller){
            return view('admin/sellers/update',compact('seller'));
        }
        return redirect()->route('admin.sellers')->withErrors(['error'=>'seller not found']);
    }
    public function seller_update(Request $request){ $this->authorize('updateSeller',auth()->guard('admin')->user());
        $seller = Seller::find($request->id);
        $roul = [
            //'name' => 'required|max:100|unique:sellers,name',
            //'email' => 'required|email|max:255|unique:sellers,email',
            'password' =>'required',
            'confirm_password' => 'same:password',
        ];
        if ($request->name != $seller->name){
            $roul +=['name' => 'required|max:100|unique:sellers,name'];
        }
        if ($request->email != $seller->email){
            $roul +=['email' => 'required|max:100|unique:sellers,email'];
        }

        $request->validate($roul);
        Seller::where('id', $request->id)->update(['updated_at'=>date_create(date('Y-m-d h:i:s')),'name'=>$request->name,'email'=>$request->email,'password'=>Hash::make($request->password),'status'=>($request->status ?? '0')  ]);

        return redirect()->route('admin.sellers')->with(['update'=>'ok']);
    }
    public function sellers_delete(Request $request){ $this->authorize('deleteSeller',auth()->guard('admin')->user());

        $seller = Seller::find($request->id);
        if($seller){
            $id = $seller->id;
            Product::where('seller_id',$id)->delete();
            $seller->delete();
            return response()->json(['status'=>true,'id'=>$id],200);
        }
        return response()->json(['status'=>false],404);
    }


    public function products(){ $this->authorize('viewProduct',auth()->guard('admin')->user());
        $products = Product::with(['category','currencie'])->get();
        return view('admin/products/all',compact('products'));
    }
    public function products_add(){ $this->authorize('addProduct',auth()->guard('admin')->user());
        $sellers = Seller::select(['id','name'])->get();
        $categorys  = Category::select(['id','name'])->get();
        $currencies = Currencie::where('status',1)->get();
        return view('admin/products/add',compact(['sellers','categorys','currencies']));
    }
    public function products_add_new(Request $request){ $this->authorize('addProduct',auth()->guard('admin')->user());
        $ss =  Seller::pluck('id');
        $s_id='';
        foreach ($ss as $s){
            $s_id .=$s.',';
        }
        $cc =  Category::pluck('id');
        $c_id='';
        foreach ($cc as $c){
            $c_id .=$c.',';
        }
        $request->validate([
            'name' => 'required|max:100',
            'title' => 'required|max:255',
            'seller_id' =>'required|in:'.$s_id,
            'category_id' =>'required|in:'.$c_id,
            'currencie_id' =>'required',
            'quantity' =>'required|numeric|gt:0',
            'photo' =>'required|image|mimes:jpeg,png,jpg,gif,svg',
            'price' =>'required|numeric|gt:0',
        ]);
        $photo_path = $request->photo->hashName();
        $request->photo->move('.\products_photo',$photo_path);

        $p= Product::create([
            'name'=>$request->name,
            'title'=>$request->title,
            'seller_id'=>$request->seller_id,
            'category_id'=>$request->category_id,
            'currencie_id' =>$request->currencie_id,
            'quantity'=>$request->quantity,
            'photo'=>$photo_path,
            'price'=>$request->price,
            'status'=> $request->status ?? '0'
        ]);

        Image::create(['path'=>$photo_path,'imageable_id'=> $p->id , 'imageable_type'=>Product::class]);
        return redirect()->route('admin.products');
    }

    public function products_update($id){ $this->authorize('updateProduct',auth()->guard('admin')->user());
        $product = Product::find($id);
        $sellers = Seller::all();
        $categorys  = Category::select(['id','name'])->get();
        $currencies = Currencie::where('status',1)->get();
        if ($product){
            return view('admin/products/update',compact(['product','sellers','categorys','currencies']));
        }
        return redirect()->route('admin.sellers')->withErrors(['error'=>'Product not found']);
    }

    public function product_update(Request $request){ $this->authorize('updateProduct',auth()->guard('admin')->user());
        $ss =  Seller::pluck('id');
        $s_id='';
        foreach ($ss as $s){
            $s_id .=$s.',';
        }
        $cc =  Category::pluck('id');
        $c_id='';
        foreach ($cc as $c){
            $c_id .=$c.',';
        }
        $request->validate([
            'name' => 'required|max:100',-
            'title' => 'required|max:255',
            'seller_id' =>'required|in:'.$s_id,
            'category_id' =>'required|in:'.$c_id,
            'currencie_id' =>'required',
            'quantity' =>'required|numeric|gt:0',
            'photo' =>'image|mimes:jpeg,png,jpg,gif,svg',
            'price' =>'required|numeric|gt:0',
        ]);
        $product = Product::find($request->id);
        $data = [
            'name'=>$request->name,
            'title'=>$request->title,
            'seller_id'=>$request->seller_id,
            'category_id' =>$request->category_id,
            'currencie_id' =>$request->currencie_id,
            'quantity'=>$request->quantity,
            'price'=>$request->price,
            'status'=> $request->status ?? '0'
        ];
        if($request->photo){
            Facades\File::delete('./products_photo/'.$product->photo);
            $photo_path = $request->photo->hashName();
            $request->photo->move('.\products_photo',$photo_path);
            $data += ['photo'=>$photo_path];
        }
        Product::where('id',$request->id)->update($data);
        return redirect()->route('admin.products')->with(['update'=>'ok']);
    }

    public function product_delete(Request $request){ $this->authorize('deleteProduct',auth()->guard('admin')->user());

        $Product = Product::find($request->id);

        if($Product){
            $id = $Product->id;
            $Product->image->delete();
            Facades\File::delete('./products_photo/'.$Product->photo);
            $Product->delete();

            return response()->json(['status'=>true,'id'=>$id],200);
        }
        return response()->json(['status'=>false],404);
    }


    public function AllOrders(){  $this->authorize('viewOrder',auth()->guard('admin')->user());
        $orders = Order::with('user')->get();
        foreach ($orders as $o ){
            $products = json_decode($o->products_data)->products ;
            $oProducts = [];
            foreach ($products as $p){
                $product = Product::find($p->id);
                if($product){
                    $product->quantity = $p->q;
                    array_push($oProducts, $product);
                }

            }
            $o->products_data = $oProducts;
        }
        return view('admin/Orders/all',compact(['orders']));
    }
    public function MakeOrderAsComplete(Request $request){ $this->authorize('updateOrder',auth()->guard('admin')->user());
        $order = Order::find($request->id);
        if ($order){
            $order->status = 1;
            $order->save();
            return response()->json(['status'=>true,'id'=>$request->id],200);
        }
        return response()->json(['status'=>false],404);
    }






}
