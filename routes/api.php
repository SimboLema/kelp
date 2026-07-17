<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\BusinessOwner\BusinessesController;
use App\Http\Controllers\Agent\AgentLoginController;
use App\Http\Controllers\Agent\AgentRegisterController;
use App\Http\Controllers\BusinessOwner\BusinessOwnerLoginController;


Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/business/login', [BusinessOwnerLoginController::class, 'login']);
Route::post('/business/register',[BusinessesController::class,'register'])->name('admin.business.register');
Route::post('/admin/categories', [AdminCategoryController::class,'store'])->name('admin.categories.store');


Route::prefix('admin/business')->group(function () {

    Route::post('/approve/{id}', [BusinessesController::class,'approve']);
    Route::post('/reject/{id}', [BusinessesController::class,'reject']);
    Route::get('/view/{id}', [BusinessesController::class,'view']);
});
Route::get('/admin/businesses', [BusinessesController::class, 'listByStatus']);

//AGENT ROUTES
Route::post('/agent/login',[AgentLoginController::class,'login'])->name('agent.login');
Route::post('/agent/registerBusiness',[AgentRegisterController::class,'registerBusiness'])->name('agent.registerBusiness');
