<?php

namespace Maxis\EloquentGuard\Contracts;

use Illuminate\Database\Events\QueryExecuted;

interface Reporter
{
    public function handleNPlusOne(QueryExecuted $query, string $source, array $data): void;

    public function handleSlowQuery(QueryExecuted $query, string $source, array $data): void;
}
