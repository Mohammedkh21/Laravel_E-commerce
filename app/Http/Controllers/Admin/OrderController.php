<?php

namespace App\Http\Controllers\Admin;

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
    public function OrderPage(){
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
        return view('admin/Orders/all',compact(['orders']));
    }
    public function MakeOrderAsComplete(Request $request){
        $order = Order::findOrFail($request->id);
        $order->status = 1;
        $order->save();
        return response()->json(['status'=>true,'id'=>$request->id],200);
    }
}
