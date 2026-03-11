<?php

namespace Maxis\EloquentGuard\Reporters;

use Maxis\EloquentGuard\Contracts\Reporter;
use Illuminate\Database\Events\QueryExecuted;
use Maxis\EloquentGuard\Jobs\SendTelegramNotification;

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
        SendTelegramNotification::dispatch($message);
    }
}
