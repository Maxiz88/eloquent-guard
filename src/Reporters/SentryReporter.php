<?php

namespace Maxis\EloquentGuard\Reporters;

use Maxis\EloquentGuard\Contracts\Reporter;
use Illuminate\Database\Events\QueryExecuted;

class SentryReporter implements Reporter
{
    public function handleNPlusOne(QueryExecuted $query, string $source, array $data): void
    {
        $this->capture('Eloquent N+1 Detected', $query, $source, $data);
    }

    public function handleSlowQuery(QueryExecuted $query, string $source, array $data): void
    {
        $this->capture('Eloquent Slow Query', $query, $source, $data);
    }

    protected function capture(string $message, $query, $source, $data): void
    {
        if (app()->bound('sentry')) {
            \Sentry\withScope(function (\Sentry\State\Scope $scope) use ($message, $source, $data) {
                $scope->setExtra('source', $source);
                $scope->setExtra('data', $data);
                $scope->setTag('package', 'eloquent-guard');

                \Sentry\captureMessage($message);
            });
        }
    }
}
