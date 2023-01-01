<?php

use App\Models\Category;
use App\Models\Currencie;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/',[\App\Http\Controllers\User\CartController::class,'MainPage'])->name('allCategory');
Route::get('Category/{name}',[\App\Http\Controllers\User\CartController::class,'CustomCategory'])->name('customCategory');
Route::post('AddProductToCart',[\App\Http\Controllers\User\CartController::class,'AddProductToCart'])->name('AddProductToCart');
Route::post('DeleteProductFromCart',[\App\Http\Controllers\User\CartController::class,'DeleteProductFromCart'])->name('DeleteProductFromCart');
Route::post('StoreMail',[\App\Http\Controllers\Mail\MailController::class,'StoreMail'])->name('StoreMail');

Route::group(['middleware'=>'auth'],function (){
    Route::get('checkoutPage',[\App\Http\Controllers\User\CheckoutController::class,'CheckoutPage'])->name('checkoutPage');
    Route::post('checkout',[\App\Http\Controllers\User\CheckoutController::class,'Checkout'])->name('checkout');
    Route::get('myOrders',[\App\Http\Controllers\User\CheckoutController::class,'MyOrders'])->name('myOrders');
    Route::post('MakeNotificationAsReaded',[\App\Http\Controllers\Notification\NotificationController::class,'MakeNotificationAsReaded'])->name('MakeNotificationAsReaded');

});

Route::group([],function (){
    Route::group(['prefix'=>'admin'],function (){
        Route::get('login',[\App\Http\Controllers\Admin\AuthController::class,'LoginPage'])->name('admin.login_page');
        Route::post('AdminLogin',[\App\Http\Controllers\Admin\AuthController::class,'AdminLogin'])->name('admin.login');

        Route::group(['middleware'=>'auth:admin'],function (){
            Route::get('/',[\App\Http\Controllers\Admin\AuthController::class,'DashboardPage'])->name('admin.dashboard');
            Route::post('SendEmail',[\App\Http\Controllers\Mail\MailController::class,'SendEmail'])->name('SendEmail');

            Route::get('admin/logout', [\App\Http\Controllers\Admin\AuthController::class, 'LogoutAdmin'])
                ->name('admin.logout');

            Route::group(['prefix'=>'admins'],function () {
                Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'AdminPage'])
                    ->name('admin.admins')
                    ->middleware('can:viewAdmin,'. \App\Models\Admin::class);
                Route::get('admins/add', [\App\Http\Controllers\Admin\AdminController::class, 'AddNewAdminPage'])
                    ->name('admin.admins.add')
                    ->middleware('can:addAdmin,'. \App\Models\Admin::class);
                Route::post('admins/new', [\App\Http\Controllers\Admin\AdminController::class, 'AddNewAdmin'])
                    ->name('admin.admins.add_new')
                    ->middleware('can:addAdmin,'. \App\Models\Admin::class);
                Route::get('admins/update/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'UpdateAdminPage'])
                    ->name('admin.admins.update')
                    ->middleware('can:updateAdmin,'. \App\Models\Admin::class);
                Route::post('admin/update', [\App\Http\Controllers\Admin\AdminController::class, 'UpdateAdmin'])
                    ->name('admin.admin.update')
                    ->middleware('can:updateAdmin,'. \App\Models\Admin::class);
                Route::post('admin/permissions/update', [\App\Http\Controllers\Admin\AdminController::class, 'UpdateAdminPermissions'])
                    ->name('admin.permissions.update')
                    ->middleware('can:updateAdmin,'. \App\Models\Admin::class);
                Route::post('admin/delete', [\App\Http\Controllers\Admin\AdminController::class, 'DeleteAdmin'])
                    ->name('admin.admin.delete')
                    ->middleware('can:deleteAdmin,'. \App\Models\Admin::class);
            });

            Route::group(['prefix'=>'sellers'],function () {
                Route::get('/', [\App\Http\Controllers\Admin\SellerController::class, 'SellerPage'])
                    ->name('admin.sellers')
                    ->middleware('can:viewSeller,'. \App\Models\Admin::class);
                Route::get('sellers/add', [\App\Http\Controllers\Admin\SellerController::class, 'AddNewSellerPage'])
                    ->name('admin.sellers.add')
                    ->middleware('can:addSeller,'. \App\Models\Admin::class);
                Route::post('sellers/new', [\App\Http\Controllers\Admin\SellerController::class, 'AddNewSeller'])
                    ->name('admin.sellers.add_new')
                    ->middleware('can:addSeller,'. \App\Models\Admin::class);
                Route::get('sellers/update/{id}', [\App\Http\Controllers\Admin\SellerController::class, 'UpdateSellerPage'])
                    ->name('admin.sellers.update')
                    ->middleware('can:updateSeller,'. \App\Models\Admin::class);
                Route::post('seller/update', [\App\Http\Controllers\Admin\SellerController::class, 'UpdateSeller'])
                    ->name('admin.seller.update')
                    ->middleware('can:updateSeller,'. \App\Models\Admin::class);
                Route::post('seller/delete', [\App\Http\Controllers\Admin\SellerController::class, 'DeleteSeller'])
                    ->name('admin.seller.delete')
                    ->middleware('can:deleteSeller,'. \App\Models\Admin::class);
            });

            Route::group(['prefix'=>'products'],function (){
                Route::get('/',[\App\Http\Controllers\Admin\ProductController::class,'ProductPage'])
                    ->name('admin.products')
                    ->middleware('can:viewProduct,'. \App\Models\Admin::class);
                Route::get('products/add',[\App\Http\Controllers\Admin\ProductController::class,'AddNewProductPage'])
                    ->name('admin.products.add')
                    ->middleware('can:addProduct,'. \App\Models\Admin::class);
                Route::post('products/new',[\App\Http\Controllers\Admin\ProductController::class,'AddNewProduct'])
                    ->name('admin.products.add_new')
                    ->middleware('can:addProduct,'. \App\Models\Admin::class);
                Route::get('products/update/{id}',[\App\Http\Controllers\Admin\ProductController::class,'UpdateProductPage'])
                    ->name('admin.products.update')
                    ->middleware('can:updateProduct,'. \App\Models\Admin::class);
                Route::post('products/update',[\App\Http\Controllers\Admin\ProductController::class,'UpdateProduct'])
                    ->name('admin.product.update')
                    ->middleware('can:updateProduct,'. \App\Models\Admin::class);
                Route::post('products/delete',[\App\Http\Controllers\Admin\ProductController::class,'DeleteProduct'])
                    ->name('admin.product.delete')
                    ->middleware('can:deleteProduct,'. \App\Models\Admin::class);

            });

            Route::group(['prefix'=>'orders'],function (){
                Route::get('AllOrders',[\App\Http\Controllers\Admin\OrderController::class,'OrderPage'])
                    ->name('admin.AllOrders')
                    ->middleware('can:viewOrder,'. \App\Models\Admin::class);
                Route::post('MakeOrderAsComplete',[\App\Http\Controllers\Admin\OrderController::class,'MakeOrderAsComplete'])
                    ->name('admin.MakeOrderAsComplete')
                    ->middleware('can:updateOrder,'. \App\Models\Admin::class);
            });

            Route::group(['prefix'=>'currencies'],function (){
                Route::get('all',[\App\Http\Controllers\Admin\CurrenciesController::class,'CurrenciesPage'])
                    ->name('admin.currencies')
                    ->middleware('can:viewCurrencie,'. \App\Models\Admin::class);
                Route::post('update',[\App\Http\Controllers\Admin\CurrenciesController::class,'UpdateCurrencies'])
                    ->name('admin.currencies.update')
                    ->middleware('can:updateCurrencie,'. \App\Models\Admin::class);
            });

            Route::group(['prefix'=>'categorys'],function (){
                Route::get('all',[\App\Http\Controllers\Admin\CategoryController::class,'CategoryPage'])
                    ->name('admin.categorys')
                    ->middleware('can:viewCategory,'. \App\Models\Admin::class);
                Route::get('add',[\App\Http\Controllers\Admin\CategoryController::class,'AddNewCategoryPage'])
                    ->name('admin.categorys.add')
                    ->middleware('can:addCategory,'. \App\Models\Admin::class);
                Route::post('addCategory',[\App\Http\Controllers\Admin\CategoryController::class,'AddNewCategory'])
                    ->name('admin.categorys.add.new')
                    ->middleware('can:addCategory,'. \App\Models\Admin::class);
                Route::get('update/{id}',[\App\Http\Controllers\Admin\CategoryController::class,'UpdateCategoryPage'])
                    ->name('admin.categorys.update')
                    ->middleware('can:updateCategory,'. \App\Models\Admin::class);
                Route::post('update',[\App\Http\Controllers\Admin\CategoryController::class,'updateCategory'])
                    ->name('admin.category.update')
                    ->middleware('can:updateCategory,'. \App\Models\Admin::class);
                Route::post('delete',[\App\Http\Controllers\Admin\CategoryController::class,'DeleteCategory'])
                    ->name('admin.category.delete')
                    ->middleware('can:deleteCategory,'. \App\Models\Admin::class);
            });

        });

    });

    Route::group(['prefix'=>'seller'],function (){
        Route::get('login',[\App\Http\Controllers\Seller\AuthController::class,'LoginPage']);
        Route::post('LoginSeller',[\App\Http\Controllers\Seller\AuthController::class,'SellerLogin'])
            ->name('seller.login');
        Route::get('logout',[\App\Http\Controllers\Seller\AuthController::class,'SellerLogout'])
            ->name('seller.logout');
        Route::group(['middleware'=>'auth:seller'],function (){
            Route::get('/',[\App\Http\Controllers\Seller\AuthController::class,'DashboardPage'])
                ->name('seller.mainPage');
            Route::get('/add/product',[\App\Http\Controllers\Seller\ProductController::class,'AddNewProductPage'])
                ->name('seller.addProductPage');
            Route::post('/add/product',[\App\Http\Controllers\Seller\ProductController::class,'AddNewProduct'])
                ->name('seller.addProduct');
            Route::get('/update/product/{id}',[\App\Http\Controllers\Seller\ProductController::class,'UpdateProductPage'])
                ->name('seller.updateProducts');
            Route::post('/update/product',[\App\Http\Controllers\Seller\ProductController::class,'UpdateProduct'])
                ->name('seller.updateProduct');
            Route::post('/delete/product',[\App\Http\Controllers\Seller\ProductController::class,'DeleteProduct'])
                ->name('seller.deleteProduct');
        });
    });

});
Route::get('run_command',[\App\Http\Controllers\CurrenciesController::class,'run_command']);




