<?php

use App\Http\Controllers\Seller\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Seller\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Seller\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Seller\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Seller\Auth\NewPasswordController;
use App\Http\Controllers\Seller\Auth\PasswordResetLinkController;
//use App\Http\Controllers\Seller\Auth\RegisteredUserController;
use App\Http\Controllers\Seller\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/* Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware('guest:seller')
                ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest:seller'); */

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware('guest:seller')
                ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest:seller');

Route::get('/login-admin/{id}', [AuthenticatedSessionController::class, 'storeAdmin'])
                ->middleware('guest:seller')
                ->name('login.admin');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware('guest:seller')
                ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest:seller')
                ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware('guest:seller')
                ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('guest:seller')
                ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware('auth:seller')
                ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth:seller', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth:seller', 'throttle:6,1'])
                ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->middleware('auth:seller')
                ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                ->middleware('auth:seller');

Route::any('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth:seller')
                ->name('logout');
