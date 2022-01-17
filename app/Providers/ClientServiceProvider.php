<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Client\DashboradService;

class ClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DashboradService::class, function($app) {
            return new DashboradService();
        });
    }
}
