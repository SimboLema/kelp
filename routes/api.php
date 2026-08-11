<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\BusinessOwner\BusinessesController;
use App\Http\Controllers\Agent\AgentLoginController;
use App\Http\Controllers\Agent\AgentDashboardController;
use App\Http\Controllers\Agent\AgentRegisterController;
use App\Http\Controllers\Api\V1\KelpApp\KelpAuthController;
use App\Http\Controllers\Api\V1\KelpApp\InsuranceOrderController;
use App\Http\Controllers\Api\V1\KelpApp\KelpAppController;
use App\Http\Controllers\Api\V1\KelpApp\KelpFavouriteController;
use App\Http\Controllers\BusinessOwner\BusinessOwnerLoginController;
use App\Http\Controllers\User\UserCategoriesController;
use App\Http\Controllers\Api\V1\KelpApp\PaymentController;

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
Route::get('/categories', [AgentRegisterController::class, 'categories']);
Route::get('/agent/dashboard', [AgentDashboardController::class, 'index']);


//USERS ROUTES kelp_app
Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [KelpAuthController::class, 'register']);
    Route::post('/auth/login', [KelpAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [KelpAuthController::class, 'me']);
        Route::post('/auth/logout', [KelpAuthController::class, 'logout']);
        Route::get('/favourites', [KelpFavouriteController::class, 'index']);
        Route::post('/favourites', [KelpFavouriteController::class, 'store']);
        Route::delete('/favourites/{business}', [KelpFavouriteController::class, 'destroy']);

        //insurance
        // Dropdowns — live pass-through to Suretech (SuretechService::getInsurers/getInsuranceTypes/getProducts/getCoverages)
        Route::get('/insurers', [InsuranceOrderController::class, 'insurers']);
        Route::get('/insurances', [InsuranceOrderController::class, 'insurances']);
        Route::get('/products/{insurance}', [InsuranceOrderController::class, 'products']);
        Route::get('/coverages/{product}', [InsuranceOrderController::class, 'coverages']);
        Route::get('/motor-categories', [InsuranceOrderController::class, 'motorCategories']);
        Route::get('/cover-note-durations', [InsuranceOrderController::class, 'coverNoteDurations']);
        Route::get('/countries', [InsuranceOrderController::class, 'countries']);
        Route::get('/regions', [InsuranceOrderController::class, 'regions']);
        Route::get('/districts/{region}', [InsuranceOrderController::class, 'districts']);
        Route::get('/policy-holder-types', [InsuranceOrderController::class, 'policyHolderTypes']);
        Route::get('/policy-holder-id-types', [InsuranceOrderController::class, 'policyHolderIdTypes']);

        // Orders — saved locally, then transmitted to Suretech (IncomingInsuranceOrderController)
        Route::post('/insurance-orders', [InsuranceOrderController::class, 'store']);
        Route::get('/insurance-orders', [InsuranceOrderController::class, 'myOrders']);
        Route::get('/insurance-orders/{id}', [InsuranceOrderController::class, 'show']);

        // Premium calculation — live call to Suretech (premium-calculate)
        Route::post('kelp-app/premium/calculate', [InsuranceOrderController::class, 'calculatePremium']);

        // Motor verification — live call to Suretech (motor-verify)
        Route::post('kelp-app/motor/verify', [InsuranceOrderController::class, 'verifyMotor']);


        //ipf
        Route::get('ipf/plans', [InsuranceOrderController::class, 'ipfPlans']);
        Route::get('ipf/accounts', [InsuranceOrderController::class, 'myIpfAccounts']);
        Route::get('orders/{order}/ipf-account', [InsuranceOrderController::class, 'ipfAccount']);
        Route::post('orders/{order}/ipf-payments', [InsuranceOrderController::class, 'recordIpfPayment']);
        
        //initiate payment
        Route::post(
            '/payments/initiate',
            [PaymentController::class, 'initiate'],
        );


        Route::post(
            '/payments/status',
            [PaymentController::class, 'status'],
        );

        // Reviews
        Route::post('/businesses/{businessId}/reviews',[UserCategoriesController::class, 'storeReview']);
    });

    Route::get('/home-feed', [KelpAppController::class, 'homeFeed']);
    Route::get('/services', [KelpAppController::class, 'services']);
    Route::get('/services/{category}/businesses', [KelpAppController::class, 'serviceBusinesses']);
    Route::get('/businesses', [KelpAppController::class, 'businesses']);
    Route::get('/businesses/{business}', [KelpAppController::class, 'businessDetails']);
});
