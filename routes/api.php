<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ProductBulletsController;
use App\Http\Controllers\ProductLinesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\QuotationsController;
use App\Http\Controllers\QuotationProductsController;
use App\Http\Controllers\SalesRepresentativeController;
use App\Http\Controllers\SubdealersController;

use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('test', [TestController::class, 'test']);

Route::post('auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AccountsController::class, 'register']);

// /api/auth
Route::prefix('auth')->middleware('auth:sanctum')->controller(AuthController::class)->group(function() {
    // /api/auth/logout
    Route::post('logout', 'logout');

    // /api/auth/check
    Route::get('check', 'check');
});


// /api/product-lines
Route::prefix('product-lines')->middleware('auth:sanctum')->controller(ProductLinesController::class)->group(function() {
    Route::get('/', 'index');

    // /api/product-lines/{id}/full
    Route::get('{id}/full', 'full');
});

// /api/products
Route::prefix('products')->middleware('auth:sanctum')->controller(ProductsController::class)->group(function() {
    Route::get('{productLineId?}', 'index');

    // /api/products/{productId}/details
    Route::get('{productId}/details', 'show');

    // /api/product/create
    Route::post('create', 'store');

    // /api/products/{id}/update
    Route::post('{id}/update', 'update');

    // /api/products/{productId}
    Route::delete('{productId}', 'destroy');

    // /api/products/{productId}/add-bullet
    Route::post('{productId}/add-bullet', 'addBullet');
});

// /api/product-bullets
Route::prefix('product-bullets')->middleware('auth:sanctum')->controller(ProductBulletsController::class)->group(function() {
    // /api/product-bullets/{productId}/create
    Route::post('{productId}/create', 'store');

    // /api/product-bullets/{bulletId}/update
    Route::post('{bulletId}/update', 'update');

    // /api/product-bullets/{bulletId}
    Route::delete('{bulletId}', 'destroy');

    // /api/product-bullets/{bulletId}/move-up
    Route::post('{bulletId}/move-up', 'moveUp');

    // /api/product-bullets/{bulletId}/move-down
    Route::post('{bulletId}/move-down', 'moveDown');
});


// /api/sales-representatives
Route::prefix('sales-representatives')->middleware('auth:sanctum')->controller(SalesRepresentativeController::class)->group(function() {
    Route::get('/', 'index');

    // /api/sales-representative/create
    Route::post('create', 'store');

    // /api/sales-representativess/{id}/update
    Route::post('{id}/update', 'update');

    // /api/sales-representativess/{id}
    Route::get('{id}', 'show');

    // /api/sales-representative/{id}
    Route::delete('{id}', 'destroy');
});

// /api/subdealers
Route::prefix('subdealers')->middleware('auth:sanctum')->controller(SubdealersController::class)->group(function() {
    Route::get('/', 'index');

    // /api/subdealers/create
    Route::post('create', 'store');

    // /api/subdealers/{id}/update
    Route::post('{id}/update', 'update');

    // /api/subdealers/{id}
    Route::get('{id}', 'show');

    // /api/subdealers/{id}
    Route::delete('{id}', 'destroy');
});

// /api/customers
Route::prefix('customers')->middleware('auth:sanctum')->controller(CustomersController::class)->group(function() {
    Route::get('/', 'index');

    // /api/customers/{customerId}
    Route::get('{customerId}', 'show');

    // /api/customers/generate-crn
    Route::get('generate-crn', 'generateCRN');

    // /api/customers/create
    Route::post('create', 'store');

    // /api/customers/{customerId}/update
    Route::post('{customerId}/update', 'update');

    // /api/customers/{customerId}
    Route::delete('{customerId}', 'destroy');
});

// /api/quotations
Route::prefix('quotations')->middleware('auth:sanctum')->controller(QuotationsController::class)->group(function() {
    Route::get('/', 'index');

    // /api/quotations/create
    Route::post('create', 'store');

    // /api/quotations/{quotationId}/update
    Route::post('{quotationId}/update', 'update');

    // /api/quotations/{quotationId}/edit
    Route::get('{quotationId}/edit', 'edit');

    // /api/quotations/{quotationId}
    Route::delete('{quotationId}', 'destroy');
});

// /api/quotation-products
Route::prefix('quotation-products')->middleware('auth:sanctum')->controller(QuotationProductsController::class)->group(function() {
    // /api/quotation-products/{quotationId}/create
    Route::post('{quotationId}/create', 'store');

    // /api/quotation-products/{quotationId}/update
    Route::post('{quotationId}/update', 'update');

    // /api/quotation-products/{quotationId}/edit
    Route::get('{quotationId}/edit', 'edit');

    // /api/quotation-products/{quotationId}
    Route::delete('{quotationId}', 'destroy');
});

