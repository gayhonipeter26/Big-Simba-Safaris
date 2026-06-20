<?php

namespace App\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class TikTokProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['user.info.basic'];

    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ',';

    /**
     * Get the authorization URL for the request.
     *
     * @param  string  $state
     */
    protected function getAuthUrl($state): string
    {
        // Generate PKCE code verifier and challenge
        $codeVerifier = $this->generateCodeVerifier();
        // Store verifier in session for later token exchange
        $this->request->session()->put('tiktok_code_verifier', $codeVerifier);

        return $this->buildAuthUrlFromBase('https://www.tiktok.com/v2/auth/authorize/', $state);

    }

    /**
     * Get the token URL for the request.
     */
    protected function getTokenUrl(): string
    {
        return 'https://open.tiktokapis.com/v2/oauth/token/';
    }

    /**
     * Get the GET/POST fields for the authorization request.
     *
     * @param  string|null  $state
     */
    protected function getCodeFields($state = null): array
    {
        $fields = [
            'client_key' => $this->clientId, // TikTok uses client_key instead of client_id
            'redirect_uri' => $this->redirectUrl,
            'scope' => $this->formatScopes($this->getScopes(), $this->scopeSeparator),
            'response_type' => 'code',
        ];

        // Add PKCE parameters
        $codeVerifier = $this->request->session()->get('tiktok_code_verifier');
        if ($codeVerifier) {
            $codeChallenge = $this->generateCodeChallenge($codeVerifier);
            $fields['code_challenge'] = $codeChallenge;
            $fields['code_challenge_method'] = 'S256';
        }

        if ($this->usesState()) {
            $fields['state'] = $state;
        }

        return $fields;
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param  string  $code
     */
    protected function getTokenFields($code): array
    {
        // Retrieve PKCE verifier from session
        $codeVerifier = $this->request->session()->get('tiktok_code_verifier');
        $fields = [
            'client_key' => $this->clientId, // TikTok uses client_key instead of client_id
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirectUrl,
        ];
        if ($codeVerifier) {
            $fields['code_verifier'] = $codeVerifier;
        }

        return $fields;
    }

    /**
     * Generate a random code verifier for PKCE.
     */
    protected function generateCodeVerifier(): string
    {
        // Generate a high-entropy random string (43-128 characters)
        return rtrim(strtr(base64_encode(random_bytes(64)), '+/', '-_'), '=');
    }

    /**
     * Generate the code challenge from a verifier using SHA256 and base64url.
     */
    protected function generateCodeChallenge(string $verifier): string
    {
        return rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '=');
    }

    protected function getUserByToken($token): array
    {
        $response = $this->getHttpClient()->get('https://open.tiktokapis.com/v2/user/info/', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
            'query' => [
                'fields' => 'open_id,union_id,avatar_url,display_name',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     */
    protected function mapUserToObject(array $user): User
    {
        $data = $user['data']['user'] ?? [];

        return (new User)->setRaw($user)->map([
            'id' => $data['open_id'] ?? null,
            'nickname' => $data['display_name'] ?? null,
            'name' => $data['display_name'] ?? null,
            'email' => null, // TikTok API v2 does not return email via basic scope
            'avatar' => $data['avatar_url'] ?? null,
        ]);
    }
}
