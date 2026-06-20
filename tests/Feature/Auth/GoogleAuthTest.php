<?php

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as SocialiteUser;

test('google redirect route redirects to google', function () {
    $response = $this->get(route('google.redirect'));

    $response->assertRedirect();
    $this->assertStringContainsString('accounts.google.com', $response->getTargetUrl());
});

test('google login logs in existing user', function () {
    $user = User::factory()->create([
        'google_id' => 'google-id-123',
    ]);

    $socialiteUser = Mockery::mock(SocialiteUser::class);
    $socialiteUser->id = 'google-id-123';
    $socialiteUser->email = $user->email;
    $socialiteUser->name = 'Google Scout';
    $socialiteUser->avatar = 'avatar-url';

    $provider = Mockery::mock(GoogleProvider::class);
    $provider->shouldReceive('user')->andReturn($socialiteUser);

    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

    $response = $this->get(route('google.callback'));

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard'));
});

test('google login redirects existing email without google link to verification page', function () {
    $user = User::factory()->create([
        'google_id' => null,
    ]);

    $socialiteUser = Mockery::mock(SocialiteUser::class);
    $socialiteUser->id = 'google-id-123';
    $socialiteUser->email = $user->email;
    $socialiteUser->name = 'Google Scout';
    $socialiteUser->avatar = 'avatar-url';

    $provider = Mockery::mock(GoogleProvider::class);
    $provider->shouldReceive('user')->andReturn($socialiteUser);

    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

    $response = $this->get(route('google.callback'));

    $response->assertRedirect(route('google.verify'));
});
