<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\ReportsExportController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignedFileController;
use App\Http\Controllers\StaticController;
use App\Models\Booking;
use App\Models\ChangeRequest;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\Package;
use App\Models\Quotation;
use App\Models\Trip;
use App\Models\Vendor;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');
Route::get('/healthz', HealthController::class)->name('healthz');

Route::get('/packages', [PackagesController::class, 'index'])->name('packages.index');
Route::get('/packages/{slug}', [PackagesController::class, 'show'])->name('packages.show');
Route::get('/about', [StaticController::class, 'about'])->name('about');
Route::get('/contact', [StaticController::class, 'contact'])->name('contact');
Route::post('/contact', [StaticController::class, 'contactStore'])->middleware('throttle:5,1')->name('contact.store');

/*
|--------------------------------------------------------------------------
| Authenticated staff area
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->middleware('role:admin|agent')->group(function () {
        // Customer master (M2)
        Route::get('/customers', fn () => view('admin.customers.index'))->name('customers.index');
        Route::get('/customers/create', fn () => view('admin.customers.create'))->name('customers.create');
        Route::get('/customers/{ulid}/edit', fn (string $ulid) => view('admin.customers.edit', compact('ulid')))->name('customers.edit');
        Route::get('/customers/{ulid}', function (string $ulid) {
            $customer = Customer::where('ulid', $ulid)->firstOrFail();

            return view('admin.customers.show', compact('customer'));
        })->name('customers.show');

        // Packages (M4)
        Route::get('/packages', fn () => view('admin.packages.index'))->name('packages.index');
        Route::get('/packages/create', fn () => view('admin.packages.create'))->name('packages.create');
        Route::get('/packages/{ulid}/edit', fn (string $ulid) => view('admin.packages.edit', compact('ulid')))->name('packages.edit');
        Route::get('/packages/{ulid}', function (string $ulid) {
            $package = Package::withTrashed()->where('ulid', $ulid)->firstOrFail();

            return view('admin.packages.show', compact('package'));
        })->name('packages.show');

        // Enquiries (M6)
        Route::get('/enquiries', fn () => view('admin.enquiries.index'))->name('enquiries.index');
        Route::get('/enquiries/{ulid}', function (string $ulid) {
            $enquiry = Enquiry::where('ulid', $ulid)->firstOrFail();

            return view('admin.enquiries.show', compact('enquiry'));
        })->name('enquiries.show');

        // Trips (M7)
        Route::get('/trips', fn () => view('admin.trips.index'))->name('trips.index');
        Route::get('/trips/create', fn () => view('admin.trips.create'))->name('trips.create');
        Route::get('/trips/{ulid}', function (string $ulid) {
            $trip = Trip::where('ulid', $ulid)->firstOrFail();

            return view('admin.trips.show', compact('trip'));
        })->name('trips.show');
        Route::get('/quotations/{ulid}/editor', function (string $ulid) {
            $quotation = Quotation::with('trip.customer')->where('ulid', $ulid)->firstOrFail();

            return view('admin.trips.quotation-editor', compact('quotation'));
        })->name('quotations.editor');

        // Bookings (M9)
        Route::get('/bookings', fn () => view('admin.bookings.index'))->name('bookings.index');
        Route::get('/bookings/create', fn () => view('admin.bookings.create'))->name('bookings.create');
        Route::get('/bookings/{ulid}/edit', fn (string $ulid) => view('admin.bookings.edit', compact('ulid')))->name('bookings.edit');
        Route::get('/bookings/{ulid}', function (string $ulid) {
            $booking = Booking::where('ulid', $ulid)->firstOrFail();

            return view('admin.bookings.show', compact('booking'));
        })->name('bookings.show');
        Route::post('/vouchers/{booking}', [VoucherController::class, 'generate'])->name('vouchers.generate');
        Route::post('/vouchers/{booking}/{document}/email', [VoucherController::class, 'email'])->name('vouchers.email');
        Route::post('/bookings/{booking}/voucher', [VoucherController::class, 'generate'])->name('bookings.voucher.generate');
        Route::post('/bookings/{booking}/voucher/{document}/email', [VoucherController::class, 'email'])->name('bookings.voucher.email');

        // Change Requests (M12)
        Route::get('/change-requests', fn () => view('admin.change-requests.index'))->name('change-requests.index');
        Route::get('/change-requests/{ulid}/edit', function (string $ulid) {
            $changeRequest = ChangeRequest::where('ulid', $ulid)->firstOrFail();

            return view('admin.change-requests.edit', compact('changeRequest'));
        })->name('change-requests.edit');
        Route::get('/change-requests/{ulid}', function (string $ulid) {
            $changeRequest = ChangeRequest::where('ulid', $ulid)->firstOrFail();

            return view('admin.change-requests.edit', compact('changeRequest'));
        })->name('change-requests.show');

        // Supplier Docs (M11)
        Route::get('/supplier-docs/new', fn () => view('admin.supplier-docs.new'))->name('supplier-docs.new');
        Route::get('/supplier-docs', fn () => view('admin.supplier-docs.index'))->name('supplier-docs.index');

        // Reports (M14)
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/bookings.xlsx', [ReportsExportController::class, 'bookings'])->name('reports.export.bookings');

        // Admin-only sub-group
        Route::middleware('role:admin')->group(function () {
            Route::view('/settings', 'admin.settings')->name('settings');

            // Vendor master (M3)
            Route::get('/vendors', fn () => view('admin.vendors.index'))->name('vendors.index');
            Route::get('/vendors/create', fn () => view('admin.vendors.create'))->name('vendors.create');
            Route::get('/vendors/{ulid}/edit', fn (string $ulid) => view('admin.vendors.edit', compact('ulid')))->name('vendors.edit');
            Route::get('/vendors/{ulid}', function (string $ulid) {
                $vendor = Vendor::where('ulid', $ulid)->firstOrFail();

                return view('admin.vendors.show', compact('vendor'));
            })->name('vendors.show');

            // Reports admin-only
            Route::get('/reports/ai-extraction', [ReportsController::class, 'aiExtraction'])->name('reports.ai-extraction');
            Route::get('/reports/sales-profit.xlsx', [ReportsExportController::class, 'salesProfit'])->name('reports.export.salesProfit');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Signed-URL file download
|--------------------------------------------------------------------------
*/

Route::get('/files/{token}', [SignedFileController::class, 'download'])
    ->middleware(['auth', 'signed'])
    ->name('files.download');

require __DIR__.'/auth.php';
