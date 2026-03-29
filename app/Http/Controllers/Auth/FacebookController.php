<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeToThePride;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    /**
     * Redirect the user to the Facebook authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     */
    public function callback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            // Try to find existing user by their Facebook ID first
            $user = User::where('facebook_id', $facebookUser->getId())->first();

            if (! $user) {
                // Fall back — check if the email already exists
                $user = User::where('email', $facebookUser->getEmail())->first();

                if ($user) {
                    // Link this Facebook account to the existing user
                    $user->update([
                        'facebook_id' => $facebookUser->getId(),
                        'avatar' => $user->avatar ?? $facebookUser->getAvatar(),
                    ]);
                } else {
                    // Brand new user — create account
                    $user = User::create([
                        'name' => $facebookUser->getName(),
                        'email' => $facebookUser->getEmail(),
                        'facebook_id' => $facebookUser->getId(),
                        'avatar' => $facebookUser->getAvatar(),
                        'password' => Hash::make(Str::random(24)),
                        'email_verified_at' => now(), // Facebook emails are pre-verified
                    ]);

                    Mail::to($user->email)->send(new WelcomeToThePride($user));
                }
            }

            // Update last reconnaisance tracking
            $user->update([
                'last_recon_at' => now(),
                'last_recon_ip' => request()->ip(),
                'last_recon_browser' => request()->header('User-Agent'),
            ]);

            Auth::login($user, true);

            if ($user->is_admin) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Facebook authentication failed. Please try again or use another sign-in method.',
            ]);
        }
    }
}
