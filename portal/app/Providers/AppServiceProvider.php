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
use App\Models\User;
use App\Services\Pdf\DompdfRenderer;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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

        VerifyEmail::createUrlUsing(function (object $notifiable): string {
            $routeName = ($notifiable instanceof User && $notifiable->user_type === User::TYPE_CUSTOMER)
                ? 'customer.verification.verify'
                : 'verification.verify';

            return URL::temporarySignedRoute(
                $routeName,
                Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ],
            );
        });
    }
}
