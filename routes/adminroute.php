<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('home');
Route::get('client/full-images/view/{clientid}/{iamgeid}', [App\Http\Controllers\Client\ImagesController::class, 'mergedImage'])->name('client.fullimage.view');

Route::prefix('admin')->group(function(){
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('admin.login');

    Route::middleware('auth:web')->group(function(){
        Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('admin.logout');
        Route::prefix('organization')->group(function(){
            Route::get('list', [App\Http\Controllers\Admin\OrganizationController::class, 'orgList'])->name('org.list');
            Route::post('/server-list', [App\Http\Controllers\Admin\OrganizationController::class, 'serverList'])->name('org.server.list');
            Route::get('/add-edit/{id?}', [App\Http\Controllers\Admin\OrganizationController::class, 'getOrgById'])->name('org.add.edit');
            Route::post('save',[App\Http\Controllers\Admin\OrganizationController::class, 'upsertOrg'])->name('org.upsert');
        });
        Route::prefix('service')->group(function(){
            Route::get('list', [App\Http\Controllers\Admin\ServiceController::class, 'list'])->name('service.list');
            Route::post('/server-list', [App\Http\Controllers\Admin\ServiceController::class, 'serverList'])->name('service.server.list');
            Route::get('/add-edit/{id?}', [App\Http\Controllers\Admin\ServiceController::class, 'getDataById'])->name('service.add.edit');
            Route::post('save',[App\Http\Controllers\Admin\ServiceController::class, 'upsertService'])->name('service.upsert');
            Route::get('/delete/{id?}', [App\Http\Controllers\Admin\ServiceController::class, 'deleteById'])->name('service.delete');
        });
        
        Route::prefix('master-data')->group(function(){
            Route::get('list', [App\Http\Controllers\Admin\AdminMasterController::class, 'list'])->name('admin.master.list');
            Route::post('/server-list', [App\Http\Controllers\Admin\AdminMasterController::class, 'serverList'])->name('admin.master.server.list');
            Route::get('/add-edit/{id?}', [App\Http\Controllers\Admin\AdminMasterController::class, 'getDataById'])->name('admin.master.add.edit');
            Route::post('save',[App\Http\Controllers\Admin\AdminMasterController::class, 'upsert'])->name('admin.master.upsert');
        });
        
        Route::prefix('client')->group(function(){
            Route::get('list', [App\Http\Controllers\Admin\ClientController::class, 'list'])->name('client.list');
            Route::post('/server-list', [App\Http\Controllers\Admin\ClientController::class, 'serverList'])->name('client.server.list');
            Route::get('/add-edit/{id?}', [App\Http\Controllers\Admin\ClientController::class, 'getDataById'])->name('client.add.edit');
            Route::post('save',[App\Http\Controllers\Admin\ClientController::class, 'upsert'])->name('client.upsert');
            Route::get('delete/{id}',[App\Http\Controllers\Admin\ClientController::class, 'delete'])->name('client.delete');
        });
        
        Route::prefix('ajax')->group(function(){
            Route::post('/delete/image', [App\Http\Controllers\Admin\AdminMasterController::class, 'deleteImage'])->name('ajax.master.delete.image');
        });
    });
});    

