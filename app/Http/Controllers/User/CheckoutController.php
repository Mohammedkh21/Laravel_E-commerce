<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function PriceCoinToUsd($CoinName): int{
        return (\Illuminate\Support\Facades\Http::get('https://open.er-api.com/v6/latest/USD'))['rates'][$CoinName];
    }

    public function CheckoutPage(){
        if (! count(  json_decode(Session::get('products'))->products  )  ){
            return  redirect()->back();
        }
        $categorys = Category::all();
        $CartProductSession = [];
        if (  isset(  json_decode(Session::get('products'))->products  )){
            $CartProductSession = json_decode(Session::get('products'))->products ;
        }
        $CartProduct = [];
        $total = 0;
        foreach ($CartProductSession as $product){
            $product1 = Product::with('currencie')->find($product->id);
            if($product){
                $product1->quantity = $product->q;
                $total +=  $product1->quantity * ($product1->price / $this->PriceCoinToUsd( (String) $product1->currencie->cc))  ;
                array_push($CartProduct , $product1);
            }
        }
        $total = ( number_format($total,3,',') );
        return view('user/checkout',compact(['CartProduct','total','categorys']));
    }

    public function Checkout(Request $request){
        $request->validate(['pay_method'=>'required','address'=>'required']);
        $CartProductSession = json_decode(Session::get('products'))->products ;

        $total =0;
        foreach ($CartProductSession as $product){
            $product1 = Product::with('currencie')->find($product->id);
            if($product1){
                $product1->quantity = $product->q;
                $total +=  $product1->quantity * ($product1->price / $this->PriceCoinToUsd( (String) $product1->currencie->cc))  ;
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

    public function MyOrders(){
        $categorys = Category::all();
        $CartProductSession = [];
        if (  isset(  json_decode(Session::get('products'))->products  )){
            $CartProductSession = json_decode(Session::get('products'))->products ;
        }
        $CartProduct = [];
        $total = 0;
        foreach ($CartProductSession as $product){
            $product1 = Product::with('currencie')->find($product->id);
            if($product1){
                $product1->quantity = $product->q;
                $total +=  $product1->quantity * ($product1->price / $this->PriceCoinToUsd( (String) $product1->currencie->cc))  ;
                array_push($CartProduct , $product1);
            }
        }
        $total = ( number_format($total,3,',') );

        $orders = Order::where('user_id',Auth::id())->get();
        foreach ($orders as $order ){
            $products = json_decode($order->products_data)->products ;
            $OrdersProducts = [];
            foreach ($products as $product){
                $product1 = Product::find($product->id);
                if($product1){
                    $product1->quantity = $product->q;
                    array_push($OrdersProducts, $product1);
                }
            }
            $order->products_data = $OrdersProducts;
        }
        return view('user/orders',compact(['orders','categorys','total','CartProduct']));
    }
}
