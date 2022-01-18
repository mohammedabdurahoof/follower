<?php

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
Route::group(['prefix' => 'customer'],function(){
    Route::post('login',[App\Http\Controllers\API\AuthController::class, 'login'])->name('api.login.customer');
    Route::group(['middleware' => ['auth:customer']],function(){
        Route::post('logout', [App\Http\Controllers\API\AuthController::class, 'logout'])->name('api.customer.logout');
        Route::post('organization/client/list',[App\Http\Controllers\API\OrgClientListController::class, 'getCustomerOrganization'])->name('api.customer.org.client');
        Route::post('organization/clients/services',[App\Http\Controllers\API\OrgClientListController::class, 'getClientsServices'])->name('api.customer.clients.services');
        Route::post('datum/list',[App\Http\Controllers\API\DataListController::class, 'getListWithService'])->name('api.customer.datum.list');
    });
});
