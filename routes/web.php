<?php
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminAgentController;
use App\Http\Controllers\Admin\BusinessOwner\BusinessesController;
use App\Http\Controllers\BusinessOwner\BusinessOwnerDashboardController;
use App\Http\Controllers\BusinessOwner\BusinessOwnerLoginController;
use App\Http\Controllers\BusinessOwner\BusinessOwnerReviewController;
use App\Http\Controllers\User\UserCategoriesController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Admin\AdminIpfPlanController;
use App\Http\Controllers\Admin\IpfSettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/',[UserDashboardController::class,'index']);

Route::get('/admin/login',[AdminLoginController::class,'showLoginForm'])->name('admin.login');

Route::get('/admin/dashboard',[AdminDashboardController::class,'index'])->name('admin.dashboard');
Route::get('/admin/businessOwner/register',[BusinessesController::class,'ShowRegistrationForm'])->name('admin.businessOwner.register');
Route::get('/admin/businessOwner/list',[BusinessesController::class,'index'])->name('admin.businessOwner.index');
Route::get('/admin/categories', [AdminCategoryController::class,'index'])->name('admin.categories');
Route::get('/admin/agents',[AdminAgentController::class,'index'])->name('admin.agents');
Route::post('/admin/agent/create',[AdminAgentController::class,'create'])->name('admin.agents.create');

Route::get('/user/categories/{id}', [UserCategoriesController::class,'show'])->name('categories.show');
Route::get('/user/business/{business}', [UserCategoriesController::class, 'details'])->name('details.show');

Route::post('/business/{id}/review', [UserCategoriesController::class, 'storeReview'])->name('reviews.store');

Route::get('/ipf/settings', [IpfSettingsController::class, 'current']);
Route::get('/ipf/settings/history', [IpfSettingsController::class, 'history']);
Route::post('/ipf/settings', [IpfSettingsController::class, 'update']);

Route::get('/ipf/plans', [AdminIpfPlanController::class, 'index'])->name('admin.ipf');
Route::get('/ipf/plans/summary', [AdminIpfPlanController::class, 'summary']);
Route::get('/ipf/plans/{id}', [AdminIpfPlanController::class, 'show']);

Route::post('/ipf/plans/{id}/payments', [
    AdminIpfPlanController::class,
    'recordPayment'
]);

Route::post('/ipf/plans/{planId}/transactions/{transactionId}/waive', [
    AdminIpfPlanController::class,
    'waivePenalty'
]);

Route::post('/ipf/plans/{id}/default', [
    AdminIpfPlanController::class,
    'markDefaulted'
]);


// The route your controller is looking for
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');

// The callback route where Google sends the user back
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');


//BUSINESS OWNER ROUTES

//Login
Route::get('/business/loginForm',[BusinessOwnerLoginController::class,'index'])->name('business.login');
Route::post('/business/login',[BusinessOwnerLoginController::class,'login'])->name('business.login.submit');

Route::middleware('auth')->group(function(){
    Route::get('/business/dashboard',[BusinessOwnerDashboardController::class,'index'])->name('businessOwner.dashboard');
    Route::get('/business/reviews',[BusinessOwnerReviewController::class,'viewReviews'])->name('business.reviews');
    Route::post('/business/reviews/{review}/reply', [BusinessOwnerReviewController::class, 'reply'])->name('business.review.reply');
});


