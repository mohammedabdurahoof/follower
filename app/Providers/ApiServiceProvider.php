<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\API\AuthService;
use App\Services\API\OrgclientListService;
use App\Services\API\ListService;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AuthService::class, function() {
            return new AuthService();
        });
        $this->app->singleton(OrgclientListService::class, function() {
            return new OrgclientListService();
        });
        $this->app->singleton(ListService::class, function() {
            return new ListService();
        });
    }
}
