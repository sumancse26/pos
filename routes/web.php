<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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

Route::get('/dashboard', [DashboardController::class, 'dashboardPage'])->middleware(TokenVerificationMiddleware::class);
