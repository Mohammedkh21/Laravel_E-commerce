<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class OrderController extends Controller
{
    public function all(){
        Order::firstOrCreate(['user_id'=>auth()->id(),'status'=>'0' or '1'],
            [
            'user_id'=>auth()->id(),
            'status'=>'0',
        ]);
        $categorys = Category::all();
        $products = Product::all();
        $order = Order::where(['user_id'=>auth()->id(),'status'=>'1'])->first();
        $oa = json_decode($order->products_data)->products;
        $OrderProducts = [];
        foreach ($oa as $p){
            $pp = Product::find($p->id);
            $pp->quantity = $p->q;
            array_push($OrderProducts , $pp);
        }
        //$OrderProducts = Product::whereIn('id',$array)->get();
        return view('user/BuyProducts',compact(['products','OrderProducts','categorys']));
    }

    public function AddToCart(Request $request){
        if($request->quantity <=0){
            return  response()->json(['status'=>false],404);
        }
        $quantity = $request->quantity   ;
        $id = $request->id  ;
        $OrderProducts = [];

        $order = Order::where(['user_id'=>auth()->id(),'status'=>'1'])->first();
        $oa = json_decode($order->products_data)->products;
        $bool = 0;
        foreach ($oa as $p){
            if($p->id == $id ){
                $bool = 1;
            }
        }
        if($bool){
            $oa = collect($oa)->map( function ($value, $key) use ($id,$quantity) {
                if ($value->id == $id){
                    $value->q += $quantity;
                }
                return $value;
            });
            $pd = ['products'=>array()];
            foreach ($oa as $p){
                $pd['products'] []=$p ;
            }
            $order ->products_data = $pd;
            $order->save();
        }else{
            $pd = ['products'=>array()];
            foreach ($oa as $p){
                $pd['products'] []=$p ;
            }
            $pd['products'] []= array("id"=>$id,"q"=>$quantity);
             $order ->products_data = $pd;
            $order->save();
            $pp = Product::find($id);
            $pp->quantity = $quantity;
            array_push($OrderProducts , $pp);
        }


        foreach ($oa as $p){
            $pp = Product::find($p->id);
            $pp->quantity = $p->q;
            array_push($OrderProducts , $pp);
        }

        return  response()->json(['status'=>true,'data'=>$OrderProducts]);

    }
    public function DeleteFromCart(Request $request){
        $id = $request->id;
        $order = Order::where(['user_id'=>auth()->id(),'status'=>'1'])->first();
        if(! isset($order)){
            return response()->json(['status'=>false],404);
        }
        $oa = json_decode($order->products_data)->products;
        $oa = collect($oa)->filter( function ($value, $key) use ($id) {
            return $value->id != $id;
        });
        $pd = ['products'=>array()];
        foreach ($oa as $p){
            $pd['products'] []=$p ;
        }
        $order ->products_data = $pd;
        $order->save();
        return response()->json(['status'=>true,'id'=>$id]);
    }







    public function PriceCoinToUsd($CoinName): int{
        return (\Illuminate\Support\Facades\Http::get('https://open.er-api.com/v6/latest/USD'))['rates'][$CoinName];

    }







    public function all2(){
        $categorys = Category::all();
        $products = Product::with('currencie')->where('quantity','>','0')->get();
        $oa = [];
        if (  isset(  json_decode(Session::get('products'))->products  )){
            $oa = json_decode(Session::get('products'))->products ;
        }
        $OrderProducts = [];
        $total = 0;
        foreach ($oa as $p){
            $pp = Product::with(['currencie'])->find($p->id);
            if ($pp){
                $pp->quantity = $p->q;
                $total +=  $pp->quantity * ($pp->price / $this->PriceCoinToUsd( (String) $pp->currencie->cc))  ;
                array_push($OrderProducts , $pp);
            }
        }
        $total = ( number_format($total,3,',') );
        return view('user/BuyProducts',compact(['products','OrderProducts','total','categorys']));
    }
    public function customCategory($name){
        $categorys = Category::all();
        $Category = Category::with(['product'=>function($q){
            return $q->with('currencie')->where('quantity','>','0')->get();;
        }])->where('name',$name)->first();
        $products = $Category->product;
        $oa = [];
        if (  isset(  json_decode(Session::get('products'))->products  )){
            $oa = json_decode(Session::get('products'))->products ;
        }
        $OrderProducts = [];
        $total = 0;
        foreach ($oa as $p){
            $pp = Product::with('currencie')->find($p->id);
            if ($pp){
                $pp->quantity = $p->q;
                $total  +=  $pp->quantity *  ($pp->price / $this->PriceCoinToUsd( (String) $pp->currencie->cc) )  ;
                array_push($OrderProducts , $pp);
            }
        }
        $total = ( number_format($total,3,',') );
        return view('user/BuyProducts',compact(['products','OrderProducts','total','categorys']));
    }
    public function AddToCart2(Request $request){
        if($request->quantity <=0){
            return  response()->json(['status'=>false],404);
        }
        $quantity = $request->quantity ?? 5  ;
        $id = $request->id  ?? 6;
        $OrderProducts = [];
        $oa = [];
        if (  isset(  json_decode(Session::get('products'))->products  )){
            $oa = json_decode(Session::get('products'))->products ;
        }
        $bool = false;
        foreach ($oa as $p){
            if($p->id == $id ){
                $bool = true;
            }
        }
        if($bool){
            $oa = collect($oa)->map( function ($value, $key) use ($id,$quantity) {
                if ($value->id == $id){
                    $value->q += $quantity;
                }
                return $value;
            });
            $pd = ['products'=>array()];
            foreach ($oa as $p){
                $pd['products'] []=$p ;
            }
            Session::put('products', json_encode($pd));
        }else{
            $pd = ['products'=>array()];
            foreach ($oa as $p){
                $pd['products'] []=$p ;
            }
            $pd['products'] []=  (object) ["id"=>"$id","q"=>"$quantity"] ;
            $oa = $pd['products'];
            Session::put('products',json_encode($pd));
        }
        $total = 0;
        //return $oa;
        foreach ($oa as $p){
            $pp = Product::with('currencie')->find($p->id);
            if ($pp){
                $pp->quantity = $p->q ;
                $total +=  $pp->quantity * ($pp->price / $this->PriceCoinToUsd( (String) $pp->currencie->cc))  ;
                array_push($OrderProducts , $pp);
            }
        }
        $total = ( number_format($total,3,',') );
        return  response()->json(['status'=>true,'data'=>$OrderProducts,'total_price'=>$total]);
    }

    public function DeleteFromCart2(Request $request){
        $id = $request->id ?? 8;
        $oa = [];
        if (  isset(  json_decode(Session::get('products'))->products  )){
            $oa = json_decode(Session::get('products'))->products ;
        }else{
            return response()->json(['status'=>false],404);
        }
        $bool = false;

        foreach ($oa as $p){
            if($p->id == $id ){
                $bool = true;
            }
        }
        if( $bool ){
            $oa = collect($oa)->filter( function ($value, $key) use ($id) {
                return $value->id != $id;
            });

            $pd = ['products'=>array()];
            $total = 0;
            foreach ($oa as $p){
                $pd['products'] []=$p ;
                $pp = Product::with('currencie')->find($p->id);
                if ($pp){
                    $total +=  $p->q * ($pp->price / $this->PriceCoinToUsd( (String) $pp->currencie->cc))  ;
                }
            }
            $total = ( number_format($total,3,',') );
            Session::put('products',json_encode($pd));
            return response()->json(['status'=>true,'id'=>$id,'total_price'=>$total]);
        }
        return response()->json(['status'=>false],404);
    }


    public function checkoutPage(){
        if ( (! isset(  json_decode(Session::get('products'))->products) or (count(  json_decode(Session::get('products'))->products  )==0 ) )){
            return  redirect()->back();
        }
        $categorys = Category::all();
        $oa = [];
        if (  isset(  json_decode(Session::get('products'))->products  )){
            $oa = json_decode(Session::get('products'))->products ;
        }
        $OrderProducts = [];
        $total = 0;
        foreach ($oa as $p){
            $pp = Product::with('currencie')->find($p->id);
            if($pp){
                $pp->quantity = $p->q;
                $total +=  $pp->quantity * ($pp->price / $this->PriceCoinToUsd( (String) $pp->currencie->cc))  ;
                array_push($OrderProducts , $pp);
            }
        }
        $total = ( number_format($total,3,',') );
        return view('user/checkout',compact(['OrderProducts','total','categorys']));
    }
    public function checkout(Request $request){
        $request->validate(['pay_method'=>'required','address'=>'required']);
        $oa = json_decode(Session::get('products'))->products ;
        $total =0;
        foreach ($oa as $p){
            $pp = Product::with('currencie')->find($p->id);
            if($pp){
                $pp->quantity = $p->q;
                $total +=  $pp->quantity * ($pp->price / $this->PriceCoinToUsd( (String) $pp->currencie->cc))  ;
            }
        }
        Order::create([
            'user_id'=>auth()->id(),
            'products_data'=>Session::get('products'),
            'price'=>$total,
            'status'=>'0',
            'pay_method'=>$request->pay_method,
            'address'=>$request->address,
        ]);
        Session::forget('products');
        return redirect()->route('myOrders');
    }
    public function myOrders(){
        $categorys = Category::all();
        $oa = [];
        if (  isset(  json_decode(Session::get('products'))->products  )){
            $oa = json_decode(Session::get('products'))->products ;
        }
        $OrderProducts = [];
        $total = 0;
        foreach ($oa as $p){
            $pp = Product::with('currencie')->find($p->id);
            if($pp){
                $pp->quantity = $p->q;
                $total +=  $pp->quantity * ($pp->price / $this->PriceCoinToUsd( (String) $pp->currencie->cc))  ;
                array_push($OrderProducts , $pp);
            }
        }
        $total = ( number_format($total,3,',') );
        $orders = Order::where('user_id',Auth::id())->get();
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

        return view('user/orders',compact(['OrderProducts','total','categorys','orders']));

    }















}













