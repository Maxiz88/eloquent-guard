<?php

namespace Maxis\EloquentGuard\Reporters;

use Maxis\EloquentGuard\Contracts\Reporter;
use Maxis\EloquentGuard\Jobs\SendSlackNotification;
use Illuminate\Database\Events\QueryExecuted;

class SlackReporter implements Reporter
{
    public function handleNPlusOne(QueryExecuted $query, string $source, array $data): void
    {
        $payload = [
            'text' => "🚨 *N+1 Detected!*",
            'attachments' => [[
                'color' => '#f2c744',
                'fields' => [
                    ['title' => 'SQL', 'value' => "```{$query->sql}```"],
                    ['title' => 'Source', 'value' => $source],
                ],
            ]]
        ];

        SendSlackNotification::dispatch($payload);
    }

    public function handleSlowQuery(QueryExecuted $query, string $source, array $data): void
    {
        $payload = [
            'text' => "🔥 *Slow Query!*",
            'attachments' => [[
                'color' => '#e01e5a',
                'fields' => [
                    ['title' => 'Duration', 'value' => "{$data['time']}ms"],
                    ['title' => 'Source', 'value' => $source],
                ],
            ]]
        ];

        SendSlackNotification::dispatch($payload);
    }
}
