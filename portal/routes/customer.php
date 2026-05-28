<?php

declare(strict_types=1);

use App\Http\Controllers\Customer\CancellationController;
use App\Http\Controllers\Customer\EnquiryController;
use App\Http\Controllers\Customer\LoginController;
use App\Http\Controllers\Customer\RegisterController;
use App\Http\Controllers\Customer\VerificationController;
use App\Livewire\Customer\CustomerDashboard;
use App\Livewire\Customer\CustomerEnquiriesIndex;
use App\Livewire\Customer\CustomerProfile;
use App\Livewire\Customer\CustomerTripDetail;
use App\Livewire\Customer\CustomerTripsIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Auth Routes (prefixed /customer to avoid collision with staff /login)
|--------------------------------------------------------------------------
*/
Route::prefix('customer')->group(function () {
    Route::middleware('guest:customer')->group(function () {
        Route::get('/signup', [RegisterController::class, 'create'])->name('customer.register');
        Route::post('/signup', [RegisterController::class, 'store'])->name('customer.register.store');

        Route::get('/login', [LoginController::class, 'create'])->name('customer.login');
        Route::post('/login', [LoginController::class, 'store'])->name('customer.login.store');
    });

    Route::post('/logout', [LoginController::class, 'destroy'])->name('customer.logout');

    /*
    |--------------------------------------------------------------------------
    | Email verification (customer guard)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:customer')->group(function () {
        Route::get('/email/verify', [VerificationController::class, 'notice'])
            ->name('customer.verification.notice');

        Route::post('/email/verification-notification', [VerificationController::class, 'send'])
            ->middleware('throttle:6,1')
            ->name('customer.verification.send');
    });

    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('customer.verification.verify');
});

/*
|--------------------------------------------------------------------------
| Protected Customer Account Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:customer', 'verified.customer'])->prefix('account')->name('customer.')->group(function () {
    Route::get('/', CustomerDashboard::class)->name('account');
    Route::get('/profile', CustomerProfile::class)->name('profile');
    Route::get('/enquiries', CustomerEnquiriesIndex::class)->name('enquiries');
    Route::post('/enquiries', [EnquiryController::class, 'store'])->name('enquiries.store');

    Route::get('/trips', CustomerTripsIndex::class)->name('trips');
    Route::get('/trips/{ulid}', CustomerTripDetail::class)->name('trips.show');

    Route::post('/bookings/{ulid}/cancellation', [CancellationController::class, 'store'])->name('bookings.cancellation');
});
