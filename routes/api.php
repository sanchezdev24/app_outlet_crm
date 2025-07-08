<?php

// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Presentation\Controllers\Api\AuthController;
use App\Presentation\Controllers\Api\CustomerController;
use App\Presentation\Controllers\Api\ProductController;
use App\Presentation\Controllers\Api\CategoryController;
use App\Presentation\Controllers\Api\BrandController;
use App\Presentation\Controllers\Api\SaleController;
use App\Presentation\Controllers\Api\DashboardController;

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);
    
    // Customers
    Route::apiResource('customers', CustomerController::class);
    
    // Products
    Route::apiResource('products', ProductController::class);
    
    // Categories
    Route::apiResource('categories', CategoryController::class);
    
    // Brands
    Route::apiResource('brands', BrandController::class);
    
    // Sales
    //Route::apiResource('sales', SaleController::class);
    //Route::get('sales/customer/{customer}', [SaleController::class, 'getByCustomer']);
});