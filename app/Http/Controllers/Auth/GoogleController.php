<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminOtpMail;
use App\Mail\WelcomeToThePride;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find existing user by email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // If they already have this Google account linked, just log in
                if ($user->google_id === $googleUser->id) {
                    $user->update([
                        'last_recon_at' => now(),
                        'last_recon_ip' => request()->ip(),
                        'last_recon_browser' => request()->header('User-Agent'),
                    ]);

                    // Check if strict OTP is required
                    if ($user->requires_otp || $user->email === config('auth.admin_email')) {
                        $otp = rand(100000, 999999);
                        $token = Str::random(64);

                        $user->update([
                            'otp_code' => $otp,
                            'otp_expires_at' => now()->addMinutes(10),
                            'handshake_token' => $token,
                        ]);

                        // Send Mail
                        Mail::to($user->email)->send(new AdminOtpMail($otp, $token));

                        session([
                            'auth.id' => $user->id,
                            'auth.remember' => false,
                        ]);

                        return redirect()->route('otp.show');
                    }

                    Auth::login($user);

                    if ($user->is_admin) {
                        return redirect()->intended(route('admin.dashboard'));
                    }

                    return redirect()->intended(route('dashboard'));
                }

                // If they have an account but it's not linked to Google yet, ask for verification
                session(['google_auth_data' => [
                    'id' => $googleUser->id,
                    'email' => $googleUser->email,
                    'avatar' => $googleUser->avatar,
                    'name' => $googleUser->name,
                ]]);

                return redirect()->route('auth.google.verify');
            }

            // Create a new user record for new Google users
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'password' => Hash::make(Str::random(24)),
            ]);

            Mail::to($user->email)->send(new WelcomeToThePride($user));

            Auth::login($user);

            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Failed to authenticate with Google. Please try again.']);
        }
    }

    /**
     * Show the verification page for linking an existing account.
     */
    public function showVerify()
    {
        if (! session()->has('google_auth_data')) {
            return redirect()->route('login');
        }

        return Inertia::render('auth/VerifyGoogleLink', [
            'email' => session('google_auth_data')['email'],
        ]);
    }

    /**
     * Verify the password and link the Google account.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $authData = session('google_auth_data');

        if (! $authData) {
            return redirect()->route('login');
        }

        $user = User::where('email', $authData['email'])->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password for this account.']);
        }

        // Verification successful, link the account
        $user->update([
            'google_id' => $authData['id'],
            'avatar' => $authData['avatar'],
            'last_recon_at' => now(),
            'last_recon_ip' => $request->ip(),
            'last_recon_browser' => $request->header('User-Agent'),
        ]);

        session()->forget('google_auth_data');

        // Check if strict OTP is required
        if ($user->requires_otp || $user->email === config('auth.admin_email')) {
            $otp = rand(100000, 999999);
            $token = Str::random(64);

            $user->update([
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(10),
                'handshake_token' => $token,
            ]);

            // Send Mail
            Mail::to($user->email)->send(new AdminOtpMail($otp, $token));

            session([
                'auth.id' => $user->id,
                'auth.remember' => false,
            ]);

            return redirect()->route('otp.show');
        }

        Auth::login($user);

        if ($user->is_admin) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }
}
