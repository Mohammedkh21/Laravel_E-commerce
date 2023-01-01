<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Traits\PriceCoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    use PriceCoin;


    public function MainPage(){
        $categorys = Category::all();
        $products = Product::with('currencie')->get();
        $CartProductSession = [];
        if (  isset(  json_decode(Session::get('products'))->products  )){
            $CartProductSession = json_decode(Session::get('products'))->products ;
        }

        $CartProduct = [];
        $total = 0;
        foreach ($CartProductSession as $product){
            $Product1 = Product::with(['currencie'])->find($product->id);
            if (!$Product1){
                continue;
            }
            $Product1->quantity = $product->q;
            $total +=  $Product1->quantity * ($Product1->price / $this->PriceCoinToUsd( (String) $Product1->currencie->cc))  ;
            array_push($CartProduct , $Product1);
        }
        $total = ( number_format($total,3,',') );
        return view('user/BuyProducts',compact(['products','CartProduct','total','categorys']));
    }

    public function CustomCategory($name){
        $categorys = Category::all();
        $category = Category::where('name',$name)->firstOrFail();
        $products = Product::with('currencie')->where('category_id',$category->id)->get();
        $CartProductSession = [];
        if (  isset(  json_decode(Session::get('products'))->products  )){
            $CartProductSession = json_decode(Session::get('products'))->products ;
        }

        $CartProduct = [];
        $total = 0;
        foreach ($CartProductSession as $product){
            $Product1 = Product::with(['currencie'])->find($product->id);
            if (!$Product1){
                continue;
            }
            $Product1->quantity = $product->q;
            $total +=  $Product1->quantity * ($Product1->price / $this->PriceCoinToUsd( (String) $Product1->currencie->cc))  ;
            array_push($CartProduct , $Product1);
        }
        $total = ( number_format($total,3,',') );
        return view('user/BuyProducts',compact(['products','CartProduct','total','categorys']));
    }

    public function AddProductToCart(Request $request){
        $request->validate([
            'id'=>'required|exists:products,id',
            'quantity'=>'required|numeric|min:1'
        ]);
        $quantity = $request->quantity  ;
        $id = $request->id  ;
        $CartProductSession = [];
        if (  isset(  json_decode(Session::get('products'))->products  )){
            $CartProductSession = json_decode(Session::get('products'))->products ;
        }
        if( in_array($id, array_column($CartProductSession, 'id')) ){
            $CartProductSession = collect($CartProductSession)->map( function ($value, $key) use ($id,$quantity) {
                if ($value->id == $id){
                    $value->q += $quantity;
                }
                return $value;
            });
            Session::put('products',json_encode(['products'=>$CartProductSession]));
        }else{
            array_push($CartProductSession , ((object) ["id"=>"$id","q"=>"$quantity"]) );
            Session::put('products',json_encode(['products'=>$CartProductSession]));
        }
        $CartProduct = [];
        $total = 0;
        foreach ($CartProductSession as $product){
            $Product1 = Product::with(['currencie'])->find($product->id);
            if (!$Product1){
                continue;
            }
            $Product1->quantity = $product->q;
            $total +=  $Product1->quantity * ($Product1->price / $this->PriceCoinToUsd( (String) $Product1->currencie->cc))  ;
            array_push($CartProduct , $Product1);
        }
        $total = ( number_format($total,3,',') );
        return  response()->json(['status'=>true,'data'=>$CartProduct,'total_price'=>$total]);
    }

    public function DeleteProductFromCart(Request $request){
        $request->validate([
            'id'=>'required|exists:products,id',
        ]);
        $id = $request->id ;
        $CartProductSession = [];
        if (  isset(  json_decode(Session::get('products'))->products  )){
            $CartProductSession = json_decode(Session::get('products'))->products ;
        }

        if( in_array($id, array_column($CartProductSession, 'id')) ){
            $CartProductSession = collect($CartProductSession)->filter( function ($value, $key) use ($id) {
                return $value->id != $id;
            });

            $total = 0;
            foreach ($CartProductSession as $product){
                $product1 = Product::with('currencie')->find($product->id);
                if ($product1){
                    $total +=  $product->q * ($product1->price / $this->PriceCoinToUsd( (String) $product1->currencie->cc))  ;
                }
            }
            $total = ( number_format($total,3,',') );
            Session::put('products',json_encode(['products'=>$CartProductSession]));
            return response()->json(['status'=>true,'id'=>$id,'total_price'=>$total]);
        }
        return response()->json(['status'=>false],404);
    }


}
