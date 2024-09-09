<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemController;


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

// Invoice
Route::get('/get_invoices', [InvoiceController::class, 'getInvoices']);
Route::get('/search_invoices', [InvoiceController::class, 'searchInvoices']);
Route::get('/create_invoice', [InvoiceController::class, 'createInvoice']);
Route::post('/createInvoiceConfig', [InvoiceController::class, 'createInvoiceConfig']);

Route::get('/show_get_invoice/{id}', [InvoiceController::class, 'showGetInvoice']);
Route::post('/update_sql_edit_invoice/{id}', [InvoiceController::class, 'updateSqlEditInvoice']);

Route::delete('/delete_invoice/{id}', [InvoiceController::class, 'deleteInvoice']);
Route::delete('/CartItems/delete_invoice_item/{id}', [InvoiceController::class, 'cartItemDeleteInvoiceItem']);

// Customer
Route::get('/get_customers', [CustomerController::class, 'getCustomers']);

// Product
Route::get('/get_products', [ProductController::class, 'getProducts']);

Route::get('/CartItems', [CartItemController::class, 'cartItems']);
Route::match(['get', 'post'],'/CartItems/Insert', [CartItemController::class, 'cartItemsInsert']);
