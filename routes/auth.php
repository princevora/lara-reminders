<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\Auth\ConfirmPassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Admin\Auth\Login as AdminLogin;
use App\Livewire\Owner\Login as OwnerLogin;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
    Route::get('forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware(['guest:admin'])->group(function () {
    Route::get('admin/login', AdminLogin::class)->name('admin.auth.login');
});

Route::middleware(['guest:owner'])->group(function () {
    Route::get('owner/login', OwnerLogin::class)->name('admin.auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', VerifyEmail::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::get('confirm-password', ConfirmPassword::class)
        ->name('password.confirm');
});

Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');

Route::post('admin/auth/logout', [App\Livewire\Actions\Logout::class, 'logoutAdmin'])
    ->name('admin.auth.logout');

Route::post('owner/auth/logout', [App\Livewire\Actions\Logout::class, 'logoutOwner'])
    ->name('owner.auth.logout');
