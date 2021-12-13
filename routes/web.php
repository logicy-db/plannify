<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;

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
    Route::post('/logout', [AuthController::class, 'logoutUser'])->name('logout');
    Route::resource('profiles', ProfileController::class)->only(['create', 'store']);

    Route::middleware(['has.profile'])->group(function () {
        Route::group([
            'prefix' => 'system',
            'middleware' => 'hasSystemAccess',
            'as' => 'system.'
        ], function () {
            // Admin routes
            Route::get('/dashboard', [SystemController::class, 'dashboardView'])->name('dashboard');
            Route::resource('users', UserController::class)->only('index');
        });

        Route::resource('users', UserController::class);

        Route::post('profiles/search', [ProfileController::class, 'search'])->name('profiles.search');
        Route::resource('profiles', ProfileController::class);

        Route::post('events/{event}/participate', [EventController::class, 'participate'])
            ->name('events.participate');
        Route::post('events/{event}/cancel-participation', [EventController::class, 'cancelParticipation'])
            ->name('events.cancelParticipation');
        Route::post('events/{event}/queue', [EventController::class, 'queue'])
            ->name('events.queue');
        Route::post('events/{event}/cancel-queue', [EventController::class, 'cancelQueue'])
            ->name('events.cancelQueue');
        Route::post('events/search', [EventController::class, 'search'])->name('events.search');
        Route::resource('events', EventController::class);

        // Page routes
        Route::get('/', [PageController::class, 'home'])->name('home');
        Route::get('/projects', [PageController::class, 'home'])->name('projects');
    });
});
