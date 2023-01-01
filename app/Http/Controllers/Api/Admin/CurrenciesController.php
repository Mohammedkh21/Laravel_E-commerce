<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currencie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CurrenciesController extends Controller
{
    public function Currencies(){
        $Currencies = Currencie::all();
        return response()->json([
            'status'=>'true',
            'Currencies'=>$Currencies
        ]);
    }

    public function Currencie(Request $request){
        $Currencie = Currencie::find($request->id);
        return response()->json([
            'status'=>'true',
            'Currencie'=>$Currencie
        ]);
    }

    public function UpdateCurrencies(Request $request){
        $Currencie = Currencie::where(['id'=>$request->id])->first();
        $Currencie->status = $request->status ?? '0';
        $Currencie->save();
        return response()->json([
            'status'=>'true',
            'Currencie'=>$Currencie
        ]);
    }

}
