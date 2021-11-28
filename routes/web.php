<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth routes
// TODO: Add message handling for all pages to show error and success messages
Route::middleware(['guest'])->group(function () {
    // Only for guest users
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'loginUser']);
    // TODO: modify registration to add invite tokens or e-mail domain check
    Route::get('/registration', [AuthController::class, 'registrationView'])->name('registration');
    Route::post('/registration', [AuthController::class, 'registerUser']);
    Route::get('/forgot-password', [AuthController::class, 'forgotPasswordView'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordView'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetUserPassword'])->name('password.update');
});
Route::middleware(['auth'])->group(function () {
    // Only for logged-in users
    Route::post('/logout', [AuthController::class, 'logoutUser'])->name('logout');
});

// Page routes
Route::middleware(['auth'])->group(function () {
    // Only for logged-in users
    Route::get('/', [PageController::class, 'home'])->name('home');
});
