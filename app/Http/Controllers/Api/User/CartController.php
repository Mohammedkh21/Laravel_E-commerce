<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Traits\PriceCoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
use PriceCoin;

    public function MyCartProduct(Request $request){

        $CartProduct =  Cache::get('CartProduct'.auth()->id()) ;
        $total = Cache::get('total'.auth()->id()) ;
        return response()->json([
            'status'=>'true',
            'CartProduct'=> json_decode($CartProduct),
            'total'=>$total
        ]);
    }


    public function AddProductToCart(Request $request){
        $request->validate([
            'product_id'=>'required|exists:products,id',
            'quantity'=>'required|numeric|min:1'
        ]);
        $quantity = $request->quantity  ;
        $id = $request->product_id  ;
        $CartProductCache = [];
        if (  Cache::has('CartProduct'.auth()->id())   ){
            $CartProductCache =  json_decode(Cache::get('CartProduct'.auth()->id())) ;
        }

        if( in_array($id, array_column($CartProductCache, 'id')) ){
            $CartProductCache = collect($CartProductCache)->map( function ($value, $key) use ($id,$quantity) {
                if ($value->id == $id){
                    $value->quantity += $quantity;
                }
                return $value;
            });
            Cache::put('CartProduct'.auth()->id(),json_encode($CartProductCache),600);
        }else{
            $product = Product::with('currencie')->findOrFail($id);
            $product->quantity = $quantity;
            array_push($CartProductCache , $product );
            Cache::put('CartProduct'.auth()->id(),json_encode($CartProductCache),600);
        }
        $total = 0;
        foreach ($CartProductCache as $product){
            $total +=  $product->quantity * ($product->price / $this->PriceCoinToUsd( (String) $product->currencie->cc))  ;
        }
        Cache::put('total'.auth()->id(),$total,600);
        return response()->json([
            'status'=>'true',
            'CartProduct'=> $CartProductCache,
            'total'=>$total
        ]);
    }

    public function DeleteProductFromCart(Request $request){
        $request->validate([
            'product_id'=>'required|exists:products,id',
        ]);
        $id = $request->product_id ;
        $CartProductCache = [];
        if (  Cache::has('CartProduct'.auth()->id())   ){
            $CartProductCache =  json_decode(Cache::get('CartProduct'.auth()->id())) ;
        }

        if( in_array($id, array_column($CartProductCache, 'id')) ){
            $CartProductCache = collect($CartProductCache)->filter( function ($value, $key) use ($id) {
                return $value->id != $id;
            });

            $total = 0;
            foreach ($CartProductCache as $product){
                $total +=  $product->quantity * ($product->price / $this->PriceCoinToUsd( (String) $product->currencie->cc))  ;
            }
            Cache::put('CartProduct'.auth()->id(),json_encode($CartProductCache),600);
            Cache::put('total'.auth()->id(),$total,600);
            return response()->json([
                'status'=>'true',
                'CartProduct'=> $CartProductCache,
                'total'=>$total
            ]);
        }
    }


}
