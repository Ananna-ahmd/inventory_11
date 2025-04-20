<?php

use App\Http\Middleware\TokenVerificationMiddleWare;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DashboardController;


route::get('/', function () {
return view('OTPMail');
});
//user
Route::post('/user-registration', [UserController::class, 'UserRegistration']);
Route::post('/user-login', [UserController::class, 'UserLogin']);
Route::get('/logout', [UserController::class, 'UserLogout']);
Route::post('/send-otp', [UserController::class, 'SendOTPCode']);
Route::post('/verify-otp', [UserController::class, 'VerifyOTP']);
Route::post('/reset-password', [UserController::class, 'ResetPassword']);
Route::get('/user', [UserController::class, 'UserProfile']);
Route::PUT('/user-update', [UserController::class, 'UpdateUserProfile']);

//category
Route::post('/category-add', [CategoryController::class, 'createCategory']);
Route::get('/category-list', [CategoryController::class, 'CategoryList']);
Route::get('/category-by-id', [CategoryController::class, 'categoryById']);
Route::PUT('/category-update', [CategoryController::class, 'CategoryUpdate']);
Route::post('/category-delete', [CategoryController::class, 'CategoryDelete']);

//customer
Route::post('/customer-add', [CustomerController::class, 'CustomerCreate']);
Route::get('/customer-list', [CustomerController::class, 'CustomerList']);
Route::get('/customer-by-id', [CustomerController::class, 'CustomerById']);
Route::PUT('/customer-update', [CustomerController::class, 'CustomerUpdate']);
Route::post('/customer-delete', [CustomerController::class, 'CustomerDelete']);

//product
Route::post('/product-add', [ProductController::class, 'CreateProduct']);
Route::get('/product-list', [ProductController::class, 'ProductList']);
Route::get('/product-by-id', [ProductController::class, 'ProductById']);
Route::PUT('/product-update', [ProductController::class, 'UpdateProduct']);
Route::post('/product-delete', [ProductController::class, 'DeleteProduct']);

//invoice
Route::post('/invoice-add', [InvoiceController::class, 'CreateInvoice']);
Route::get('/invoice-select', [InvoiceController::class, 'invoiceSelect']);
Route::get('/invoice-details', [InvoiceController::class, 'InvoiceDetails']);
Route::post('/invoice-delete', [InvoiceController::class, 'DeleteInvoice']);

//Dashboard
Route::get('/summary', [DashboardController::class, 'summary']);

