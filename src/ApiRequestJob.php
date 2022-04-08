<?php

namespace BizBezzie\ZohoBooks;

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

    private string $url;
    private array $data;
    private array $headers;
    private string $method;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $method, string $url, array $data = [], array $headers = [])
    {
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
        try {
            $access_token = ZohoBooks::getAccessToken();

            $data = $this->data + ['organization_id' => config('zohobooks.organization_id')];

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

        } catch (Throwable $exception) {
            if ($this->attempts() > 3) {
                // hard fail after 3 attempts
                throw $exception;
            }
            // requeue this job to be executes in 1 minute from now
            $this->release(60);
            return;
        }
    }
}
