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

class TikTokController extends Controller
{
    /**
     * Redirect the user to the TikTok authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('tiktok')->redirect();
    }

    /**
     * Obtain the user information from TikTok.
     */
    public function callback()
    {
        try {
            $tiktokUser = Socialite::driver('tiktok')->user();

            // Find existing user by TikTok ID
            $user = User::where('tiktok_id', $tiktokUser->getId())->first();

            if ($user) {
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

                Auth::login($user, true);

                if ($user->is_admin) {
                    return redirect()->intended(route('admin.dashboard'));
                }

                return redirect()->intended(route('dashboard'));
            }

            // TikTok ID not registered yet
            // If already logged in, link TikTok account to current user
            if (Auth::check()) {
                $currentUser = Auth::user();
                $currentUser->update([
                    'tiktok_id' => $tiktokUser->getId(),
                    'avatar' => $currentUser->avatar ?? $tiktokUser->getAvatar(),
                ]);

                return redirect()->route('profile.update')->with('status', 'TikTok account linked successfully.');
            }

            // Save TikTok details in session for linking/registration
            session(['tiktok_auth_data' => [
                'id' => $tiktokUser->getId(),
                'name' => $tiktokUser->getName() ?? $tiktokUser->getNickname() ?? 'TikTok User',
                'avatar' => $tiktokUser->getAvatar(),
            ]]);

            return redirect()->route('tiktok.verify');

        } catch (\Exception $e) {
            \Log::error('TikTok authentication error: '.$e->getMessage());

            return redirect()->route('login')->withErrors([
                'email' => 'TikTok authentication failed. Please try again or use another sign-in method.',
            ]);
        }
    }

    /**
     * Show the verification page for linking an existing account or registering.
     */
    public function showVerify()
    {
        if (! session()->has('tiktok_auth_data')) {
            return redirect()->route('login');
        }

        return Inertia::render('auth/VerifyTikTokLink');
    }

    /**
     * Verify the email/password and link the TikTok account or create a new user.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $authData = session('tiktok_auth_data');

        if (! $authData) {
            return redirect()->route('login');
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Existing user — require password to link
            $request->validate([
                'password' => ['required', 'string'],
            ]);

            if (! Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password' => 'Incorrect password for this account.']);
            }

            // Link the account
            $user->update([
                'tiktok_id' => $authData['id'],
                'avatar' => $user->avatar ?? $authData['avatar'],
                'last_recon_at' => now(),
                'last_recon_ip' => $request->ip(),
                'last_recon_browser' => $request->header('User-Agent'),
            ]);
        } else {
            // New user — register them
            $user = User::create([
                'name' => $authData['name'],
                'email' => $request->email,
                'tiktok_id' => $authData['id'],
                'avatar' => $authData['avatar'],
                'password' => Hash::make(Str::random(24)),
                'email_verified_at' => now(),
            ]);

            Mail::to($user->email)->send(new WelcomeToThePride($user));
        }

        session()->forget('tiktok_auth_data');

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

        Auth::login($user, true);

        if ($user->is_admin) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }
}
