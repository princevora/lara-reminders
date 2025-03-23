<?php

use App\Livewire\Admin\Notifications\NotificationTypes;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Users\NotificationList;
use App\Livewire\Users\SendVenueRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::prefix('u')->name('user.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('notifications', NotificationList::class)->name('notifications');
    Route::get('send-venue-request', SendVenueRequest::class)->name('send-venue-request');
});

Route::prefix('owner')->name('owner.')->middleware(['auth:owner', 'verified'])->group(function () {
    Route::view('dashboard', 'owner-dashboard')->name('dashboard');
    // Route::get('notifications', NotificationList::class)->name('notifications');
    // Route::get('send-venue-request', SendVenueRequest::class)->name('send-venue-request');
});

Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'verified'])->group(function () {
    Route::view('dashboard', 'admin-dashboard')->name('dashboard');
    Route::get('notifications', NotificationTypes::class)->name('dashboard');
});

Route::middleware(['auth:web,admin,owner'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__ . '/auth.php';
