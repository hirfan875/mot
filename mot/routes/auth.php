<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomerNewPasswordController;

/* Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware('guest:customer')
                ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest:customer'); */

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware('guest:customer')
                ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest:customer');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware('guest:customer')
                ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest:customer')
                ->name('password.email');

Route::get('/reset-password/{token}', [CustomerNewPasswordController::class, 'create'])
                ->middleware('guest:customer')
                ->name('password.reset');

Route::post('/reset-password', [CustomerNewPasswordController::class, 'store'])
                ->middleware('guest:customer')
                ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware('auth:customer')
                ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth:customer', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth:customer', 'throttle:6,1'])
                ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->middleware('auth:customer')
                ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                ->middleware('auth:customer');

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth:customer')
                ->name('logout');
