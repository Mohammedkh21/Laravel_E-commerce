<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\User\OrderNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

class OrderController extends Controller
{
    public function Orders(Request $request){
        $orders = Order::with('user')->get();
        foreach ($orders as $order ){
            $products = json_decode($order->products_data)->products ;
            $OrderProducts = [];
            foreach ($products as $p){
                $product = Product::withTrashed()->findOrFail($p->id);
                $product->quantity = $p->q;
                array_push($OrderProducts, $product);
            }
            $order->products_data = $OrderProducts;
        }
        return response()->json([
            'status'=>'true',
            'orders'=>$orders
        ]);
    }

    public function Order(Request $request){
        $order = Order::with('user')->find($request->id);

        $products = json_decode($order->products_data)->products ;
        $OrderProducts = [];
        foreach ($products as $p){
            $product = Product::withTrashed()->findOrFail($p->id);
            $product->quantity = $p->q;
            array_push($OrderProducts, $product);
        }
        $order->products_data = $OrderProducts;

        return response()->json([
            'status'=>'true',
            'order'=>$order
        ]);
    }

    public function MakeOrderAsComplete(Request $request){
        $order = Order::findOrFail($request->id);
        $order->status = 1;
        $order->save();
        $products = json_decode($order->products_data)->products ;
        $OrderProducts = [];
        foreach ($products as $p){
            $product = Product::withTrashed()->findOrFail($p->id);
            $product->quantity = $p->q;
            array_push($OrderProducts, $product);
        }
        $order->products_data = $OrderProducts;
        return response()->json([
            'status'=>'true',
            'admins'=>$order
        ]);
    }
}
