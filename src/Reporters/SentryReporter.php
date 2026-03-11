<?php

namespace Maxis\EloquentGuard\Reporters;

use Maxis\EloquentGuard\Contracts\Reporter;
use Illuminate\Database\Events\QueryExecuted;
use Maxis\EloquentGuard\Jobs\SendSentryNotification;

class SentryReporter implements Reporter
{
    public function handleNPlusOne(QueryExecuted $query, string $source, array $data): void
    {
        $this->capture('Eloquent N+1 Detected', $query->sql, $source, $data);
    }

    public function handleSlowQuery(QueryExecuted $query, string $source, array $data): void
    {
        $this->capture('Eloquent Slow Query', $query->sql, $source, $data);
    }

    protected function capture(string $message, string $sql, string $source, array $data): void
    {
        if (str_contains(strtolower($sql), 'jobs') || str_contains(strtolower($sql), 'sentry')) {
            return;
        }

        if (app()->bound('sentry')) {
            SendSentryNotification::dispatch($message, $sql, $source, $data);
        }
    }

}
