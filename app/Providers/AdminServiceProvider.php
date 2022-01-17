<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Admin\FollowerserviceService;
use App\Services\Admin\CreatetableForCustomerServiceService;
use App\Services\Admin\ClientService;
use App\Services\Admin\AdminMasterService;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // admin services
        $this->app->singleton("App\Contracts\Admin\OrganizationContract", "App\Services\Admin\OrganizationService");
        
        $this->app->singleton(FollowerserviceService::class, function($app) {
            return new FollowerserviceService();
        });
        $this->app->singleton(CreatetableForCustomerServiceService::class, function($app) {
            return new CreatetableForCustomerServiceService();
        });
        $this->app->singleton(AdminMasterService::class, function($app) {
            return new AdminMasterService();
        });
        $this->app->singleton(ClientService::class, function($app) {
            return new ClientService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
