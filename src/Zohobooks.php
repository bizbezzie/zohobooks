<?php

namespace Bizbezzie\Zohobooks;

use Bizbezzie\Zohobooks\Jobs\ApiRequestJob;
use Bizbezzie\Zohobooks\Models\Zohoauth;
use DB;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Route;

class Zohobooks
{
    /**
     * @return RedirectResponse
     */
    public function firstToken($user_id)
    {
        $zoho_user = Zohoauth::whereUserId($user_id)->firstOrFail();

        $redirect_url = config('zohobooks.auth_url') . '?scope=' . config('zohobooks.scope') . '&client_id=' . $zoho_user->client_id . '&state=' . $user_id . '&response_type=code&redirect_uri=' . route('auth.zoho.callback') . '&access_type=offline&prompt=consent';

        return redirect()->away($redirect_url);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function callback(Request $request)
    {
        $zoho_user = Zohoauth::whereUserId($request->state)->first();

        $response = Http::asForm()->post(config('zohobooks.token_url'),
            [
                'code'          => $request->code,
                'client_id'     => $zoho_user->client_id,
                'client_secret' => $zoho_user->client_secret,
                'redirect_uri'  => route('auth.zoho.callback'),
                'grant_type'    => 'authorization_code',
                'state'         => $zoho_user->user_id,
                'scope'         => config('zohobooks.scope'),
            ]
        );

        Zohoauth::whereUserId($request->state)->update(
            [
                'token'            => $response['access_token'],
                'refresh_token'    => $response['refresh_token'],
                'token_expires_in' => now()->addSeconds($response['expires_in'] - 10),
            ]);

        session()->flash('success', "Zoho Auth Tokens Created Successfully.");

        return redirect()->route(config('zohobooks.redirect_route'));
    }

    /**
     * @param $user_id
     * @return string
     */
    public function getAccessToken($user_id): string
    {
        $zoho_user = DB::table('zohoauths')->whereUserId($user_id)->first();
        if ($zoho_user->token_expires_in < now()) {
            return $this->refreshToken($user_id);
        }

        return $zoho_user->token;
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function refreshToken($user_id)
    {
        $zoho_user = DB::table('zohoauths')->whereUserId($user_id)->first();

        $data = [
            'refresh_token' => $zoho_user->refresh_token,
            'client_id'     => $zoho_user->client_id,
            'client_secret' => $zoho_user->client_secret,
            'redirect_uri'  => route('auth.zoho.callback'),
            'grant_type'    => 'refresh_token'
        ];

        $response = Http::asForm()->acceptJson()
            ->post(config('zohobooks.token_url'), $data);

        Zohoauth::where(['user_id' => $user_id])
            ->update([
                'token'            => $response['access_token'],
                'token_expires_in' => now()->addSeconds($response['expires_in'] - 10),
            ]);
        return $response['access_token'];
    }

    /**
     * @param $user_id
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return array
     */
    public function postQueued($user_id, string $url, array $data, array $headers = []): array
    {
        ApiRequestJob::dispatch($user_id, 'post', $url, $data, $headers);

        return [
            'status'  => true,
            'message' => "Job has been scheduled.",
        ];
    }

    /**
     * @param $user_id
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return array
     */
    public function patchQueued($user_id, string $url, array $data, array $headers = []): array
    {
        ApiRequestJob::dispatch($user_id, 'patch', $url, $data, $headers);

        return [
            'status'  => true,
            'message' => "Job has been scheduled.",
        ];
    }

    /**
     * @param $user_id
     * @param string $url
     * @param string $data_key
     * @param array $data
     * @param array $headers
     * @return array
     */
    public function get($user_id, string $url, string $data_key, array $data = [], array $headers = []): array
    {
        $access_token = $this->getAccessToken($user_id);

        $organization_id = Zohoauth::whereUserId($user_id)->first()->organization_id;

        $data = $data + ['organization_id' => $organization_id];

        $response = Http::withHeaders(['Authorization' => 'Zoho-oauthtoken ' . $access_token] + $headers)
            ->get($url, $data);

        return $this->checkRequest($response, $data_key);
    }

    /**
     * @param $user_id
     * @param string $url
     * @param string|null $data_key
     * @param array $data
     * @param array $headers
     * @param bool $as_form
     * @return array
     */
    public function post($user_id, string $url, string|null $data_key = null, array $data = [], array $headers = [], bool $as_form = false): array
    {
        $access_token = $this->getAccessToken($user_id);

        $organization_id = Zohoauth::whereUserId($user_id)->first()->organization_id;

        $data = $data + ['organization_id' => $organization_id];


        $response = $as_form
            ? Http::acceptJson()->asForm()
                ->withHeaders(['Authorization' => 'Zoho-oauthtoken ' . $access_token] + $headers)
                ->post($url, $data)
            : Http::acceptJson()
                ->withHeaders(['Authorization' => 'Zoho-oauthtoken ' . $access_token] + $headers)
                ->post($url, $data);

        return $this->checkRequest($response, $data_key);
    }

    /**
     * @param $user_id
     * @param string $url
     * @param string $data_key
     * @param array $data
     * @param array $headers
     * @return array
     */
    public function patch($user_id, string $url, string $data_key, array $data = [], array $headers = []): array
    {
        $access_token = $this->getAccessToken($user_id);

        $organization_id = Zohoauth::whereUserId($user_id)->first()->organization_id;

        $data = $data + ['organization_id' => $organization_id];

        $response = Http::acceptJson()
            ->withHeaders(['Authorization' => 'Zoho-oauthtoken ' . $access_token] + $headers)
            ->patch($url, $data);

        return $this->checkRequest($response, $data_key);
    }


    /**
     * @param Response $response
     * @param string|null $data_key
     * @return array
     */
    public function checkRequest(Response $response, string|null $data_key = null): array
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
            'data'    => $response[$data_key] ?? null
        ];
    }
}
