<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Observers\CategoryObserver;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\SellerObserver;
use Illuminate\Support\ServiceProvider;

class ProviderForObservers extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Order::observe(new OrderObserver());
        Category::observe(new CategoryObserver());
        Seller::observe(new SellerObserver());
    }
}
