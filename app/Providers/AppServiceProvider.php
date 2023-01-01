<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Order;
use App\Models\User;
use App\Observers\OrderObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('OwnerProducts',function ( $admin){
            return $admin->owner == 1;
        });




    }
}
