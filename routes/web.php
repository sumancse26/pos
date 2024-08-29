<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
});

Route::post('user-registration', [UserController::class, 'UserRegistration']);
Route::post('user-login', [UserController::class, 'UserLogin']);
Route::post('sent-otp', [UserController::class, 'SendOTPCode']);
Route::post('verify-otp', [UserController::class, 'verifyOTP']);
Route::post('reset-password', [UserController::class, 'resetPassword'])
    ->middleware(TokenVerificationMiddleware::class);
Route::get('user-profile', [UserController::class, 'getUserProfile'])
    ->middleware(TokenVerificationMiddleware::class);
Route::post('user-update', [UserController::class, 'updateProfile'])
    ->middleware(TokenVerificationMiddleware::class);


#page routes comments  
Route::get('login', [UserController::class, 'loginPage']);
Route::get('registration', [UserController::class, 'registrationPage']);
Route::get('resetPassword', [UserController::class, 'resetPasswordPage']);
Route::get('sendOtp', [UserController::class, 'sendOtpPage']);
Route::get('verifyOtp', [UserController::class, 'verifyOtpPage']);
Route::get('logout', [UserController::class, 'logoutPage']);
Route::get('userProfile', [UserController::class, 'profilePage'])->middleware(TokenVerificationMiddleware::class);
Route::get('categoryPage', [CategoryController::class, 'CategoryPage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('salesPage', [InvoiceController::class, 'SalesPage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('invoicePage', [InvoiceController::class, 'InvoicePage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('dashboard', [DashboardController::class, 'dashboardPage'])->middleware(TokenVerificationMiddleware::class);


#route for category
Route::post('create-category', [CategoryController::class, 'createCategory'])->middleware(TokenVerificationMiddleware::class);
Route::post('update-category', [CategoryController::class, 'updateCategory'])->middleware(TokenVerificationMiddleware::class);
Route::post('delete-category', [CategoryController::class, 'deleteCategory'])->middleware(TokenVerificationMiddleware::class);
Route::get('get-category', [CategoryController::class, 'getCategory'])->middleware(TokenVerificationMiddleware::class);
Route::post('get-category-by-id', [CategoryController::class, 'getCategoryById'])->middleware(TokenVerificationMiddleware::class);

#route for customers
Route::get('customerPage', [CustomerController::class, 'customerPage'])->middleware([TokenVerificationMiddleware::class]);
Route::post('create-customer', [CustomerController::class, 'createCustomer'])->middleware(TokenVerificationMiddleware::class);
Route::post('update-customer', [CustomerController::class, 'updateCustomer'])->middleware(TokenVerificationMiddleware::class);
Route::post('delete-customer', [CustomerController::class, 'deleteCustomer'])->middleware(TokenVerificationMiddleware::class);
Route::get('get-customer', [CustomerController::class, 'getCustomer'])->middleware(TokenVerificationMiddleware::class);
Route::post('get-customer-by-id', [CustomerController::class, 'getCustomerById'])->middleware(TokenVerificationMiddleware::class);

#route  for products
Route::get('/productPage', [ProductController::class, 'productPage'])->middleware([TokenVerificationMiddleware::class]);
Route::post('create-product', [ProductController::class, 'createProduct'])->middleware(TokenVerificationMiddleware::class);
Route::post('update-product', [ProductController::class, 'updateProduct'])->middleware(TokenVerificationMiddleware::class);
Route::post('delete-product', [ProductController::class, 'deleteProduct'])->middleware(TokenVerificationMiddleware::class);
Route::get('get-product', [ProductController::class, 'getProduct'])->middleware(TokenVerificationMiddleware::class);
Route::post('get-product-by-id', [ProductController::class, 'getProductById'])->middleware(TokenVerificationMiddleware::class);

#route for invoices
Route::post('create-invoice', [InvoiceController::class, 'createInvoice'])->middleware(TokenVerificationMiddleware::class);
Route::post('invoice-details', [InvoiceController::class, 'invoiceDetails'])->middleware(TokenVerificationMiddleware::class);
Route::post('delete-invoice', [InvoiceController::class, 'deleteInvoice'])->middleware(TokenVerificationMiddleware::class);
Route::get('get-invoice', [InvoiceController::class, 'getInvoice'])->middleware(TokenVerificationMiddleware::class);
