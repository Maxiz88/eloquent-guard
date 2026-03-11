<?php

namespace Maxis\EloquentGuard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected string $message) {}

    public function handle(): void
    {
        $token = config('eloquent-guard.telegram.token');
        $chatId = config('eloquent-guard.telegram.chat_id');

        if ($token && $chatId) {
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $this->message,
                'parse_mode' => 'Markdown',
            ]);
        }
    }
}
