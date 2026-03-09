<?php

namespace Maxis\EloquentGuard\Reporters;

use Maxis\EloquentGuard\Contracts\Reporter;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Http;

class TelegramReporter implements Reporter
{
    public function handleNPlusOne(QueryExecuted $query, string $source, array $data): void
    {
        $this->send("🚨 *N+1 Detected!*\n\n`{$query->sql}`\n\n📍 *Source:* `{$source}`\n🔢 *Count:* {$data['count']}");
    }

    public function handleSlowQuery(QueryExecuted $query, string $source, array $data): void
    {
        $this->send("🔥 *Slow Query!*\n\n`{$query->sql}`\n\n📍 *Source:* `{$source}`\n⏱ *Time:* {$data['time']}ms");
    }

    protected function send(string $message): void
    {
        $token = config('eloquent-guard.telegram.token');
        $chatId = config('eloquent-guard.telegram.chat_id');

        if ($token && $chatId) {
            Http::post("https://api.telegram.org{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);
        }
    }
}
