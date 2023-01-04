<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'User'],function (){
    Route::post('Login',[\App\Http\Controllers\Api\User\AuthController::class,'Login']);
    Route::group(['middleware'=>['auth:sanctum','sanctum_guard:user']],function (){
        Route::post('Logout',[\App\Http\Controllers\Api\User\AuthController::class,'Logout']);
        Route::group(['prefix'=>'Cart'],function (){
            Route::get('/',[\App\Http\Controllers\Api\User\CartController::class,'MyCartProduct']);
            Route::post('add',[\App\Http\Controllers\Api\User\CartController::class,'AddProductToCart']);
            Route::post('delete',[\App\Http\Controllers\Api\User\CartController::class,'DeleteProductFromCart']);

        });
        Route::group(['prefix'=>'Checkout'],function (){
            Route::post('/',[\App\Http\Controllers\Api\User\CheckoutController::class,'Checkout']);
            Route::get('MyOrders',[\App\Http\Controllers\Api\User\CheckoutController::class,'MyOrders']);

        });
    });

});


Route::group(['prefix'=>'Seller'],function (){
    Route::post('Login',[\App\Http\Controllers\Api\Seller\AuthController::class,'Login']);
    Route::group(['middleware'=>['auth:sanctum','sanctum_guard:seller']],function (){
        Route::post('Logout',[\App\Http\Controllers\Api\Seller\AuthController::class,'Logout']);
        Route::group(['prefix'=>'product'],function (){
            Route::get('all',[\App\Http\Controllers\Api\Seller\ProductController::class,'AllProduct']);
            Route::get('product',[\App\Http\Controllers\Api\Seller\ProductController::class,'Product']);
            Route::post('add',[\App\Http\Controllers\Api\Seller\ProductController::class,'AddNewProduct']);
            Route::post('delete',[\App\Http\Controllers\Api\Seller\ProductController::class,'DeleteProduct']);

        });
    });

});






Route::group(['prefix'=>'Admin'],function (){
    Route::post('Login',[\App\Http\Controllers\Api\Admin\AuthController::class,'Login']);
    Route::group(['middleware'=>['auth:sanctum','sanctum_guard:admin']],function (){
        Route::post('Logout',[\App\Http\Controllers\Api\Admin\AuthController::class,'Logout']);
        Route::get('Admins',[\App\Http\Controllers\Api\Admin\AdminController::class,'Admins']);
        Route::get('Admin',[\App\Http\Controllers\Api\Admin\AdminController::class,'Admin']);
        Route::post('AddNewAdmin',[\App\Http\Controllers\Api\Admin\AdminController::class,'AddNewAdmin']);
        Route::post('DeleteAdmin',[\App\Http\Controllers\Api\Admin\AdminController::class,'DeleteAdmin']);

        Route::group(['prefix'=>'Category'],function (){
            Route::get('Categorys',[\App\Http\Controllers\Api\Admin\CategoryController::class,'Categorys']);
            Route::get('Category',[\App\Http\Controllers\Api\Admin\CategoryController::class,'Category']);
            Route::post('AddNewCategory',[\App\Http\Controllers\Api\Admin\CategoryController::class,'AddNewCategory']);
            Route::post('DeleteCategory',[\App\Http\Controllers\Api\Admin\CategoryController::class,'DeleteCategory']);

        });

        Route::group(['prefix'=>'Seller'],function (){
            Route::get('Sellers',[\App\Http\Controllers\Api\Admin\SellerController::class,'Sellers']);
            Route::get('Seller',[\App\Http\Controllers\Api\Admin\SellerController::class,'Seller']);
            Route::post('AddNewSeller',[\App\Http\Controllers\Api\Admin\SellerController::class,'AddNewSeller']);
            Route::post('DeleteSeller',[\App\Http\Controllers\Api\Admin\SellerController::class,'DeleteSeller']);

        });

        Route::group(['prefix'=>'Product'],function (){
            Route::get('Products',[\App\Http\Controllers\Api\Admin\ProductController::class,'Products']);
            Route::get('Product',[\App\Http\Controllers\Api\Admin\ProductController::class,'Product']);
            Route::post('AddNewProduct',[\App\Http\Controllers\Api\Admin\ProductController::class,'AddNewProduct']);
            Route::post('DeleteSeller',[\App\Http\Controllers\Api\Admin\ProductController::class,'DeleteProduct']);

        });

        Route::group(['prefix'=>'Order'],function (){
            Route::get('Orders',[\App\Http\Controllers\Api\Admin\OrderController::class,'Orders']);
            Route::get('Order',[\App\Http\Controllers\Api\Admin\OrderController::class,'Order']);
            Route::post('MakeOrderAsComplete',[\App\Http\Controllers\Api\Admin\OrderController::class,'MakeOrderAsComplete']);

        });

        Route::group(['prefix'=>'Currencies'],function (){
            Route::get('Currencies',[\App\Http\Controllers\Api\Admin\CurrenciesController::class,'Currencies']);
            Route::get('Currencie',[\App\Http\Controllers\Api\Admin\CurrenciesController::class,'Currencie']);
            Route::post('UpdateCurrencies',[\App\Http\Controllers\Api\Admin\CurrenciesController::class,'UpdateCurrencies']);

        });

    });

});
















Route::post('CheckLogin',function (Request $request){

    return response()->json($request->user() );

})->middleware('auth:sanctum');
