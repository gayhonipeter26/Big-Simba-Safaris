<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorController extends Controller
{
    /**
     * Show the OTP verification screen.
     */
    public function show(Request $request): Response|RedirectResponse
    {
        if (! session()->has('auth.id')) {
            return redirect()->route('login');
        }

        return Inertia::render('auth/VerifyOtp', [
            'email' => User::find(session('auth.id'))->email,
        ]);
    }

    /**
     * Handle the OTP verification request.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['nullable', 'string'],
        ]);

        if (! session()->has('auth.id')) {
            return redirect()->route('login');
        }

        $user = User::findOrFail(session('auth.id'));

        // Check for manual code OR handshake approval
        $isCodeValid = $request->code && $user->otp_code === $request->code && now()->isBefore($user->otp_expires_at);
        $isHandshaked = $user->handshake_at && $user->handshake_at->isAfter(now()->subMinutes(10));

        if (! $isCodeValid && ! $isHandshaked) {
            return back()->withErrors(['code' => 'Clearance denied. Please provide a valid code or authorize from your primary device.']);
        }

        // Clear security tokens
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'handshake_token' => null,
            'handshake_at' => null,
        ]);

        // Complete Login
        Auth::login($user, session('auth.remember', false));
        session()->forget(['auth.id', 'auth.remember']);

        if ($user->is_admin) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Remote handshake from primary device.
     */
    public function handshake(string $token): Response
    {
        $user = User::where('handshake_token', $token)->firstOrFail();

        $user->update([
            'handshake_at' => now(),
        ]);

        return Inertia::render('auth/HandshakeSuccess');
    }

    /**
     * Check if the handshake has been completed.
     */
    public function checkStatus(): array
    {
        if (! session()->has('auth.id')) {
            return ['status' => 'expired'];
        }

        $user = User::find(session('auth.id'));

        if ($user && $user->handshake_at && $user->handshake_at->isAfter(now()->subMinutes(10))) {
            return ['status' => 'approved'];
        }

        return ['status' => 'pending'];
    }
}
