<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\General\GeneralService;
use App\Services\General\FileuploadService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GeneralService::class, function($app) {
            return new GeneralService();
        });
        $this->app->bind(FileuploadService::class, function() {
            return new FileuploadService();
        });
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
