<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\PdfRenderer;
use App\Models\Booking;
use App\Models\ChangeRequest;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\Package;
use App\Models\Quotation;
use App\Models\Trip;
use App\Models\Vendor;
use App\Policies\BookingPolicy;
use App\Policies\ChangeRequestPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\EnquiryPolicy;
use App\Policies\PackagePolicy;
use App\Policies\QuotationPolicy;
use App\Policies\TripPolicy;
use App\Policies\VendorPolicy;
use App\Services\Pdf\DompdfRenderer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PdfRenderer::class, DompdfRenderer::class);
    }

    public function boot(): void
    {
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Vendor::class, VendorPolicy::class);
        Gate::policy(Package::class, PackagePolicy::class);
        Gate::policy(Enquiry::class, EnquiryPolicy::class);
        Gate::policy(Trip::class, TripPolicy::class);
        Gate::policy(Quotation::class, QuotationPolicy::class);
        Gate::policy(Booking::class, BookingPolicy::class);
        Gate::policy(ChangeRequest::class, ChangeRequestPolicy::class);
    }
}
