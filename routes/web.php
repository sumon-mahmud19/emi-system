<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductModelController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Users Route:
Route::resource('users', UserController::class);


Route::resource('customers', CustomerController::class);
Route::get('/customers/{id}/emi-plans', [CustomerController::class, 'customerEmiPlans'])->name('customers.emi_plans');
Route::get('/locations/{id}/customers', [CustomerController::class, 'showByLocation'])->name('location.customers');


Route::resource('purchases', PurchaseController::class);
Route::get('pdf', [PurchaseController::class, 'getpdf'])->name('pdf');
Route::get('get-models/{product_id}', [PurchaseController::class, 'getModels'])->name('get.models');
Route::get('autocomplete', [PurchaseController::class, 'autocomplete'])->name('autocomplete');

Route::resource('installments', InstallmentController::class);

Route::resource('locations', LocationController::class);


Route::resource('products', ProductController::class);

Route::resource('models', ProductModelController::class);



//!  Roles Route:
Route::resource('roles', RoleController::class);


Route::post('/pay', [PaymentController::class, 'pay'])->name('pay');

Route::get('/reports/monthly', [ReportController::class, 'monthlyReport'])
    ->name('report')
    ->middleware('role:admin');
    
    Auth::routes();
    
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('role:admin');


// ?aaz,hH.{b5p
// Route::get('invoices', InvoiceController::class);
Route::get('/pdf', [InvoiceController::class, 'getPdf']);