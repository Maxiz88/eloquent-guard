<?php

namespace Maxis\EloquentGuard\Recorders;

use Illuminate\Database\Events\QueryExecuted;
use Laravel\Pulse\Facades\Pulse;

class EloquentGuardRecorder
{
    public function record(QueryExecuted $query, string $type, array $data): void
    {
        $sql = (string) $query->sql;

        Pulse::record(
            type: 'eloquent_guard:' . $type,
            key: $sql,
            value: (int) ($data['time'] ?? $data['count'] ?? 1),
            timestamp: now()
        )->max()->count();
    }
}
