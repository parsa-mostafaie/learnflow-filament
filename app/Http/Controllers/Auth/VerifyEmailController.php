<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

/**
 * Class VerifyEmailController
 * 
 * This controller is responsible for handling email verification for authenticated users.
 */
class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     * 
     * This method is called when the user clicks on the email verification link.
     *
     * @param EmailVerificationRequest $request
     * @return RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // If the user's email is already verified, redirect to the dashboard with a verification status
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        // If the user's email is not verified, mark it as verified and trigger the Verified event
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Redirect to the dashboard with a verification status
        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
