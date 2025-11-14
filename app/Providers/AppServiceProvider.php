<?php

namespace App\Providers;

use App\Contracts\ShippingServiceInterface;
use App\Services\ShippingService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ShippingServiceInterface::class, ShippingService::class);
    }

    public function boot(): void
    {
        //
    }
}
