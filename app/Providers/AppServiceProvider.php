<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            $lowStockProducts = Product::with('stocks')
                ->get()
                ->filter(function($p) { 
                    return $p->stocks->sum('quantity') <= 20; 
                })
                ->values();

            $view->with('lowStockProducts', $lowStockProducts);
            $view->with('lowStockCount', $lowStockProducts->count());
        });
    }
}