<?php

namespace App\Http\Controllers;

use App\Models\Currencie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CurrenciesController extends Controller
{
    //

    public function all(){  $this->authorize('viewCurrencie',auth()->guard('admin')->user());
        $Currencies = Currencie::all();
        return view('admin/Currencies/all',compact('Currencies'));
    }
    public function update_status(Request $request){  $this->authorize('updateCurrencie',auth()->guard('admin')->user());
        Currencie::where(['id'=>$request->id])->update(['status'=>$request->status]);
        return response()->json(['status'=>true]);
    }

    public function run_command(){
        Artisan::call('db:seed --class=CustomCommand');
        return 'done';
    }

}
