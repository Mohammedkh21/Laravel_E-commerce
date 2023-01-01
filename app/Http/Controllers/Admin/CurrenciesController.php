<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currencie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CurrenciesController extends Controller
{
    public function CurrenciesPage(){
        $Currencies = Currencie::paginate(10);
        return view('admin/Currencies/all',compact('Currencies'));
    }
    public function UpdateCurrencies(Request $request){
        Currencie::where(['id'=>$request->id])->update(['status'=>$request->status]);
        return response()->json(['status'=>true]);
    }

}
