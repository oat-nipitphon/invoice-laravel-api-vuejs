<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get_all_invoice', [InvoiceController::class, 'getAllInvoice']);
Route::get('/search_invoice', [InvoiceController::class, 'searchInvoice']);
Route::get('/create_invoice', [InvoiceController::class, 'createInvoice']);
Route::post('/createInvoiceConfig', [InvoiceController::class, 'createInvoiceConfig']);

Route::get('/get_all_customer', [CustomerController::class, 'getAllCustomer']);

Route::get('/getProduct', [ProductController::class, 'getProduct']);

