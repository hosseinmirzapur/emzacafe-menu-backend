<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// menu section
Route::get('/branch/{branch}/menu', [BranchController::class, 'menu']);

// auth
Route::post('/login', [AdminController::class, 'login']);
Route::post('/signup', [AdminController::class, 'signup']);
Route::post('/logout', [AdminController::class, 'logout']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin', [AdminController::class, 'admin']);
    Route::post('/change-password', [AdminController::class, 'changePassword']);
});

// auth-needed routes
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/product', ProductController::class);
    Route::resource('/category', CategoryController::class);
    Route::resource('/branch', BranchController::class);
});

Route::get('/health', function () {
    return response()->json([
        'healthy' => true
    ]);
});

Route::get('config-clear', function () {
    Artisan::call('config:clear');
});

Route::get('config-cache', function () {
    Artisan::call('config:cache');
});

Route::get('cache-clear', function () {
    Artisan::call('cache:clear');
});
