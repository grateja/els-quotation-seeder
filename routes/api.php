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

// /api/customers
Route::prefix('customers')->middleware('auth:sanctum')->controller(CustomersController::class)->group(function() {
    Route::get('/', 'index');

    // /api/customers/{customer}
    Route::get('{customer}', 'show');

    // /api/customers/generate-crn
    Route::get('generate-crn', 'generateCRN');

    // /api/customers/create
    Route::post('create', 'store');

    // /api/customers/{customer}/update
    Route::post('{customer}/update', 'update');

    // /api/customers/{customer}
    Route::delete('{customer}', 'destroy');
});

// /api/product-lines
Route::prefix('product-lines')->middleware('auth:sanctum')->controller(ProductLinesController::class)->group(function() {
    Route::get('/', 'index');

    // /api/product-lines/{productLine}/full
    Route::get('{productLine}/full', 'full');
});

// /api/products
Route::prefix('products')->middleware('auth:sanctum')->controller(ProductsController::class)->group(function() {
    Route::get('{productLineId?}', 'index');

    // /api/products/{product}/details
    Route::get('{product}/details', 'show');

    // /api/product/create
    Route::post('create', 'store');

    // /api/products/{product}/update
    Route::post('{product}/update', 'update');

    // /api/products/{product}
    Route::delete('{product}', 'destroy');

    // /api/products/{product}/add-bullet
    Route::post('{product}/add-bullet', 'addBullet');
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

    // /api/sales-representativess/{salesRep}/update
    Route::post('{salesRep}/update', 'update');

    // /api/sales-representativess/{salesRep}
    Route::get('{salesRep}', 'show');

    // /api/sales-representative/{salesRep}
    Route::delete('{salesRep}', 'destroy');
});

// /api/subdealers
Route::prefix('subdealers')->middleware('auth:sanctum')->controller(SubdealersController::class)->group(function() {
    Route::get('/', 'index');

    // /api/subdealers/create
    Route::post('create', 'store');

    // /api/subdealers/{subdealer}/update
    Route::post('{subdealer}/update', 'update');

    // /api/subdealers/{subdealer}
    Route::get('{subdealer}', 'show');

    // /api/subdealers/{subdealer}
    Route::delete('{subdealer}', 'destroy');
});

// /api/quotations
Route::prefix('quotations')->middleware('auth:sanctum')->controller(QuotationsController::class)->group(function() {
    Route::get('/', 'index');

    // /api/quotations/create
    Route::post('create', 'store');

    // /api/quotations/{quotation}/update
    Route::post('{quotation}/update', 'update');

    // /api/quotations/{quotation}/edit
    Route::get('{quotation}/edit', 'edit');

    // /api/quotations/{quotation}
    Route::delete('{quotation}', 'destroy');
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

