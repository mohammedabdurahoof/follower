<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//

//
Route::prefix('client')->group(function() {
    Route::middleware('guest:client')->group(function() {
        Route::post('/login', [App\Http\Controllers\Auth\Client\LoginController::class, 'login'])->name('client.login');
    });
    
    Route::middleware(['auth:client'])->group(function(){
        Route::post('/logout', [App\Http\Controllers\Auth\Client\LoginController::class, 'logout'])->name('client.logout');
        Route::prefix('full-images')->group(function(){
            Route::get('list', [App\Http\Controllers\Client\ImagesController::class, 'list'])->name('client.fullimage.list');
            Route::post('/server-list', [App\Http\Controllers\Client\ImagesController::class, 'serverList'])->name('client.fullimage.server.list');
            Route::get('details/{id}', [App\Http\Controllers\Client\ImagesController::class, 'getDataById'])->name('client.fullimage.detail');
        });
        Route::prefix('dashboard')->group(function(){
            Route::get('view', [App\Http\Controllers\Client\DashboardController::class, 'dashboard'])->name('client.dashboard');
            Route::post('save', [App\Http\Controllers\Client\DashboardController::class, 'saveDetails'])->name('client.details.upsert');
        });
        Route::prefix('award')->group(function(){
            Route::get('list', [App\Http\Controllers\Client\AwardController::class, 'list'])->name('award.list');
            Route::post('/server-list', [App\Http\Controllers\Client\AwardController::class, 'serverList'])->name('award.server.list');
            Route::get('/add-edit/{id?}', [App\Http\Controllers\Client\AwardController::class, 'getDataById'])->name('award.add.edit');
            Route::post('save',[App\Http\Controllers\Client\AwardController::class, 'upsert'])->name('award.upsert');
        });
        Route::prefix('data-upload')->group(function(){
            Route::get('list', [App\Http\Controllers\Client\ClientDataController::class, 'list'])->name('client.data.upload.list');
            Route::post('/server-list', [App\Http\Controllers\Client\ClientDataController::class, 'serverList'])->name('client.data.upload.server.list');
            Route::get('/add-edit/{id?}', [App\Http\Controllers\Client\ClientDataController::class, 'getDataById'])->name('client.data.upload.add.edit');
            Route::post('save',[App\Http\Controllers\Client\ClientDataController::class, 'upsert'])->name('client.data.upload.upsert');
        });
        Route::prefix('customer')->group(function(){
            Route::get('list', [App\Http\Controllers\Client\CustomerController::class, 'list'])->name('customer.list');
            Route::post('/server-list', [App\Http\Controllers\Client\CustomerController::class, 'serverList'])->name('customer.server.list');
            Route::get('/add-edit/{id?}', [App\Http\Controllers\Client\CustomerController::class, 'getDataById'])->name('customer.add.edit');
            Route::post('save',[App\Http\Controllers\Client\CustomerController::class, 'upsert'])->name('customer.upsert');
        });
    });
});
