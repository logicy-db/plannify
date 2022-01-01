<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserInvitationController;

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
    Route::post('/login', [AuthController::class, 'loginUser'])->middleware('isActiveUser');
    Route::get('/registration', [AuthController::class, 'registrationView'])->name('registration')
        ->middleware('canRegistrate');
    Route::post('/registration', [AuthController::class, 'registerUser']);
    Route::get('/forgot-password', [AuthController::class, 'forgotPasswordView'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordView'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetUserPassword'])->name('password.update');
});

Route::middleware(['auth', 'isActiveUser'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logoutUser'])->name('logout');
    Route::resource('profiles', ProfileController::class)->only(['create', 'store']);

    Route::middleware(['hasProfile'])->group(function () {
        Route::group([
            'prefix' => 'system',
            'middleware' => 'hasSystemAccess',
            'as' => 'system.'
        ], function () {
            // System routes
            Route::get('/dashboard', [SystemController::class, 'dashboardView'])->name('dashboard');
            // TODO: why is it here and outside of system?
            Route::resource('users', UserController::class)->only('index');
            Route::post('invitations/{invite}/resend', [UserInvitationController::class, 'resendInvite'])
                ->name('invitations.resendInvite');
            Route::resource('invitations', UserInvitationController::class);
            Route::put('users/{user}/change-status', [UserController::class, 'changeUserStatus'])
                ->name('users.changeStatus');
        });
        // TODO: move to the system
        Route::resource('users', UserController::class);

        Route::post('profiles/search', [ProfileController::class, 'search'])->name('profiles.search');
        Route::resource('profiles', ProfileController::class)->except(['create', 'store']);

        // TODO: refactor post to put requests
        Route::post('events/{event}/participate', [EventController::class, 'participate'])
            ->name('events.participate');
        Route::put('events/{event}/cancel-participation/{user?}', [EventController::class, 'cancelParticipation'])
            ->name('events.cancelParticipation');
        Route::post('events/{event}/queue', [EventController::class, 'queue'])
            ->name('events.queue');
        Route::put('events/{event}/cancel-queue/{user?}', [EventController::class, 'cancelQueue'])
            ->name('events.cancelQueue');
        Route::post('events/search', [EventController::class, 'search'])->name('events.search');
        Route::put('events/{event}/allow-participation/{user}', [EventController::class, 'allowParticipation'])
            ->name('events.allowParticipation');
        Route::resource('events', EventController::class);

        Route::view('/','page.home')->name('home');
    });
});
