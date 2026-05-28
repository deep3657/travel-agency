<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Auth\CustomerUser;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificationController extends Controller
{
    public function notice(Request $request): View|RedirectResponse
    {
        $user = $request->user('customer');

        if ($user && $user->hasVerifiedEmail()) {
            return redirect()->route('customer.account');
        }

        return view('customer.auth.verify-email');
    }

    public function verify(Request $request, int $id, string $hash): RedirectResponse
    {
        $user = CustomerUser::findOrFail($id);

        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        if (! auth('customer')->check() || auth('customer')->id() !== $user->id) {
            auth('customer')->login($user);
        }

        return redirect()->route('customer.account')->with('status', 'email-verified');
    }

    public function send(Request $request): RedirectResponse
    {
        $user = $request->user('customer');

        if ($user && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('status', 'verification-link-sent');
    }
}
