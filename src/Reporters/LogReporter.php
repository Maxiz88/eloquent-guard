<?php

namespace Maxis\EloquentGuard\Reporters;

use Maxis\EloquentGuard\Contracts\Reporter;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;

class LogReporter implements Reporter
{
    public function handleNPlusOne(QueryExecuted $query, string $source, array $data): void
    {
        Log::warning("Eloquent Guard: N+1 detected!", [
            'sql' => $query->sql,
            'count' => $data['count'] ?? 'unknown',
            'source' => $source
        ]);
    }

    public function handleSlowQuery(QueryExecuted $query, string $source, array $data): void
    {
        Log::critical("Eloquent Guard: Slow Query!", [
            'sql' => $query->sql,
            'duration' => ($data['time'] ?? 0) . 'ms',
            'source' => $source
        ]);
    }
}
