<?php

namespace Maxis\EloquentGuard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendSlackNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected array $payload) {}

    public function handle(): void
    {
        $url = config('eloquent-guard.slack_webhook_url');

        if ($url) {
            Http::post($url, $this->payload);
        }
    }
}
