<?php

namespace Bizbezzie\Zohobooks\Jobs;

use App\Models\Log;
use Bizbezzie\Zohobooks\Facades\Zohobooks;
use Bizbezzie\Zohobooks\Models\Zohoauth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Throwable;

class ApiRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user_id;
    private string $url;
    private array $data;
    private array $headers;
    private string $method;

    /**
     * Create a new job instance.
     *
     * @param $user_id
     * @param string $method
     * @param string $url
     * @param array $data
     * @param array $headers
     */
    public function __construct($user_id, string $method, string $url, array $data = [], array $headers = [])
    {
        $this->user_id = $user_id;
        $this->url = $url;
        $this->data = $data;
        $this->headers = $headers;
        $this->method = $method;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        $access_token = Zohobooks::getAccessToken($this->user_id);

        $organization_id = Zohoauth::whereUserId($this->user_id)->first()->organization_id;

        $data = $this->data + ['organization_id' => $organization_id];

        switch ($this->method) {

            case 'post':
                Http::acceptJson()
                    ->withHeaders(['Authorization' => 'Zoho-oauthtoken ' . $access_token] + $this->headers)
                    ->post($this->url, $data);
                break;

            case 'patch':
                Http::acceptJson()
                    ->withHeaders(['Authorization' => 'Zoho-oauthtoken ' . $access_token] + $this->headers)
                    ->patch($this->url, $data);
                break;
        }
    }
}
