<?php

namespace Bizbezzie\Zohobooks;

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class Zohobooks
{
    /**
     * @return CacheManager|Application|mixed
     */
    public function firstToken(): mixed
    {
        return cache('zoho_token')
            ?? Socialite::driver('zoho')
                ->scopes(config('zohobooks.scope'))
                ->with(['access_type' => 'offline', 'response_type' => 'code', 'prompt' => 'consent'])
                ->stateless()
                ->redirect();
    }

    /**
     * @return bool|string
     */
    public function callback(): bool|string
    {
        $user = Socialite::driver('zoho')->stateless()->user();
        cache(['zoho_token' => $user->token]);
        cache(['zoho_token_expires_in' => now()->addSeconds($user->expiresIn - 10)]);
        cache(['zoho_refresh_token' => $user->refreshToken]);

        return json_encode([
            "status"  => true,
            "message" => "Token Created Successfully.",
            "data"    => [
                'zoho_token'            => cache('zoho_token'),
                'zoho_token_expires_in' => cache('zoho_token_expires_in'),
                'zoho_refresh_token'    => cache('zoho_refresh_token')
            ]]);
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        if (cache('zoho_token_expires_in') < now()) {
            $this->refreshToken();
        }

        return cache('zoho_token');
    }

    public function refreshToken()
    {
        $data = [
            'refresh_token' => cache('zoho_refresh_token'),
            'client_id'     => config('zohobooks.client_id'),
            'client_secret' => config('zohobooks.client_secret'),
            'redirect_uri'  => config('zohobooks.redirect'),
            'grant_type'    => 'refresh_token'
        ];

        $response = Http::asForm()->acceptJson()
            ->post(config('zohobooks.token_url'), $data);

        cache(['zoho_token' => $response['access_token']]);
        cache(['zoho_token_expires_in' => now()->addSeconds($response['expires_in'] - 10)]);
    }

    public function postQueued(string $url, array $data, array $headers = []): array
    {
        ApiRequestJob::dispatch('post', $url, $data, $headers);

        return [
            'status'  => true,
            'message' => "Job has been scheduled.",
        ];
    }

    public function patchQueued(string $url, array $data, array $headers = []): array
    {
        ApiRequestJob::dispatch('patch', $url, $data, $headers);

        return [
            'status'  => true,
            'message' => "Job has been scheduled.",
        ];
    }

    public function get(string $url, string $data_key, array $data = [], array $headers = []): array
    {
        $access_token = $this->getAccessToken();

        $data = $data + ['organization_id' => config('zohobooks.organization_id')];

        $response = Http::withHeaders(['Authorization' => 'Zoho-oauthtoken ' . $access_token] + $headers)
            ->get($url, $data);

        return $this->checkRequest($response, $data_key);
    }


    public function post(string $url, string $data_key, array $data = [], array $headers = []): array
    {
        $access_token = $this->getAccessToken();

        $data = $data + ['organization_id' => config('zohobooks.organization_id')];

        $response = Http::acceptJson()
            ->withHeaders(['Authorization' => 'Zoho-oauthtoken ' . $access_token] + $headers)
            ->post($url, $data);

        return $this->checkRequest($response, $data_key);
    }


    public function patch(string $url, string $data_key, array $data = [], array $headers = []): array
    {
        $access_token = $this->getAccessToken();

        $data = $data + ['organization_id' => config('zohobooks.organization_id')];

        $response = Http::acceptJson()
            ->withHeaders(['Authorization' => 'Zoho-oauthtoken ' . $access_token] + $headers)
            ->patch($url, $data);

        return $this->checkRequest($response, $data_key);
    }


    public function checkRequest(Response $response, string $data_key = 'data'): array
    {
        if ($response->failed() || !isset($response['code']) || $response['code'] != 0) {
            return [
                'status'  => false,
                'code'    => $response['code'] ?? null,
                'message' => $response['message'] ?? $response['error'],
            ];
        }
        return [
            'status'  => true,
            'message' => $response['message'],
            'data'    => $response[$data_key]
        ];
    }
}
