<?php

use App\Models\User;
use App\Socialite\TikTokProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

test('tiktok redirect route redirects to tiktok', function () {
    $response = $this->get(route('tiktok.redirect'));

    $response->assertRedirect();
    $this->assertStringContainsString('tiktok.com', $response->getTargetUrl());
});

test('tiktok login logs in existing user', function () {
    $user = User::factory()->create([
        'tiktok_id' => 'tiktok-id-123',
    ]);

    $socialiteUser = Mockery::mock(SocialiteUser::class);
    $socialiteUser->shouldReceive('getId')->andReturn('tiktok-id-123');
    $socialiteUser->shouldReceive('getName')->andReturn('TikTok Scout');
    $socialiteUser->shouldReceive('getNickname')->andReturn('tiktokscout');
    $socialiteUser->shouldReceive('getAvatar')->andReturn('avatar-url');

    $provider = Mockery::mock(TikTokProvider::class);
    $provider->shouldReceive('user')->andReturn($socialiteUser);

    Socialite::shouldReceive('driver')->with('tiktok')->andReturn($provider);

    $response = $this->get(route('tiktok.callback'));

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard'));
});

test('tiktok login redirects new user to verification page', function () {
    $socialiteUser = Mockery::mock(SocialiteUser::class);
    $socialiteUser->shouldReceive('getId')->andReturn('tiktok-new-456');
    $socialiteUser->shouldReceive('getName')->andReturn('TikTok New User');
    $socialiteUser->shouldReceive('getNickname')->andReturn('tiktoknew');
    $socialiteUser->shouldReceive('getAvatar')->andReturn('avatar-url');

    $provider = Mockery::mock(TikTokProvider::class);
    $provider->shouldReceive('user')->andReturn($socialiteUser);

    Socialite::shouldReceive('driver')->with('tiktok')->andReturn($provider);

    $response = $this->get(route('tiktok.callback'));

    $response->assertRedirect(route('tiktok.verify'));
    $this->assertEquals([
        'id' => 'tiktok-new-456',
        'name' => 'TikTok New User',
        'avatar' => 'avatar-url',
    ], session('tiktok_auth_data'));
});

test('new user can verify email and create account', function () {
    session(['tiktok_auth_data' => [
        'id' => 'tiktok-new-456',
        'name' => 'TikTok New User',
        'avatar' => 'avatar-url',
    ]]);

    $response = $this->post(route('tiktok.link'), [
        'email' => 'newuser@example.com',
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'newuser@example.com',
        'tiktok_id' => 'tiktok-new-456',
    ]);

    $user = User::where('email', 'newuser@example.com')->first();
    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard'));
});

test('existing user can link tiktok using password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    session(['tiktok_auth_data' => [
        'id' => 'tiktok-existing-789',
        'name' => 'TikTok Existing',
        'avatar' => 'avatar-url',
    ]]);

    $response = $this->post(route('tiktok.link'), [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'tiktok_id' => 'tiktok-existing-789',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard'));
});

test('existing user cannot link tiktok with wrong password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    session(['tiktok_auth_data' => [
        'id' => 'tiktok-existing-789',
        'name' => 'TikTok Existing',
        'avatar' => 'avatar-url',
    ]]);

    $response = $this->post(route('tiktok.link'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('password');
});
