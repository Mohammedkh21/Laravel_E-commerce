<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Traits\PriceCoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    use PriceCoin;

    public function Checkout(Request $request){

        $request->validate(['pay_method'=>'required','address'=>'required']);
        $CartProductCache =[];
        if (  Cache::has('CartProduct'.auth()->id())   ){
            $CartProductCache =  json_decode(Cache::get('CartProduct'.auth()->id())) ;
        }else{
            return response()->json([
                'status'=>'false',
                'message'=> 'you dont have products in your cart',
            ]);
        }

        $total = Cache::get('total'.auth()->id());
        $products_data = [];
        foreach ($CartProductCache as $product){
            array_push($products_data , ((object) ["id"=>"$product->id","q"=>"$product->quantity"]) );
        }
        Order::create([
            'user_id'=>auth()->id(),
            'products_data'=>json_encode(['products'=>$products_data]),
            'price'=>$total,
            'status'=>'0',
            'pay_method'=>$request->pay_method,
            'address'=>$request->address,
        ]);
        Cache::forget('CartProduct'.auth()->id());
        Cache::forget('total'.auth()->id());
        return response()->json([
            'status'=>'true',
            'CartProduct'=> $CartProductCache,
            'total'=>$total
        ]);
    }

    public function MyOrders(){

        $orders = Order::where('user_id',auth()->id())->get();
        foreach ($orders as $order ){
            $products = json_decode($order->products_data)->products ;
            $OrderProducts = [];
            foreach ($products as $product){
                $product1 = Product::find($product->id);
                if($product1){
                    $product1->quantity = $product->q;
                    array_push($OrderProducts, $product1);
                }
            }
            $order->products_data = $OrderProducts;
        }
        return response()->json([
            'status'=>'true',
            'orders'=> $orders,
        ]);
    }
}
