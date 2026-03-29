<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\AdminOtpMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = auth()->user();

        // Check if strict OTP is required (specifically for requested email)
        if ($user->requires_otp || $user->email === config('auth.admin_email')) {
            $otp = rand(100000, 999999);
            $token = Str::random(64);

            $user->update([
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(10),
                'handshake_token' => $token,
            ]);

            // Send Mail (handled by log mailable in development)
            Mail::to($user->email)->send(new AdminOtpMail($otp, $token));

            // Log out but keep ID in session for verification
            $sessionData = [
                'auth.id' => $user->id,
                'auth.remember' => $request->boolean('remember'),
            ];

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            session($sessionData);

            return redirect()->route('otp.show');
        }

        $request->session()->regenerate();

        // Update Recon Data
        $request->user()->update([
            'last_recon_at' => now(),
            'last_recon_ip' => $request->ip(),
            'last_recon_browser' => $request->header('User-Agent'),
        ]);

        if ($request->user()->is_admin) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
