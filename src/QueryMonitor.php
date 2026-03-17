<?php

namespace Maxis\EloquentGuard;

use Illuminate\Database\Events\QueryExecuted;
use Laravel\Pulse\Facades\Pulse;
use Maxis\EloquentGuard\Contracts\Reporter;
use Maxis\EloquentGuard\Recorders\EloquentGuardRecorder;

class QueryMonitor
{
    /**
     * History is cleared after each request
     */
    protected array $history = [];

    /**
     * Main method for handling query execution event
     */
    public function handle(QueryExecuted $query): void
    {
        $ignoredTables = config('eloquent-guard.except_tables', []);

        foreach ($ignoredTables as $table) {
            if (str_contains($query->sql, "`$table`")) {
                return;
            }
        }

        $fingerprint = $this->getFingerprint($query->sql);
        $count = ($this->history[$fingerprint] ?? 0) + 1;
        $this->history[$fingerprint] = $count;

        if ($count === (int) config('eloquent-guard.limits.n_plus_one_threshold', 5)) {
            $this->triggerReporters('nPlusOne', $query, ['count' => $count]);
        }

        $maxMs = (float) config('eloquent-guard.limits.query_duration_ms', 500);
        if ($query->time > $maxMs) {
            $this->triggerReporters('slowQuery', $query, ['time' => $query->time]);
        }
    }

    /**
     * Unified call to all registered reporters
     */
    protected function triggerReporters(string $type, QueryExecuted $query, array $data): void
    {
        $source = $this->findSource();

        if (class_exists(Pulse::class)) {
            app(EloquentGuardRecorder::class)
                ->record($query, $type, $data);
        }

        $reporters = config('eloquent-guard.reporters', []);

        $method = 'handle' . ucfirst($type);

        foreach ($reporters as $reporterClass) {
            $reporter = app($reporterClass);

            if ($reporter instanceof Reporter && method_exists($reporter, $method)) {
                $reporter->$method($query, $source, $data);
            }
        }
    }

    /**
     * Creates a unique fingerprint for the SQL query, replacing values with "?"
     */
    protected function getFingerprint(string $sql): string
    {
        return hash('xxh128', preg_replace(['/\d+/', '/\'.*?\'/'], '?', $sql));
    }

    /**
     * Find the file and line number in the application code that triggered the query
     */
    protected function findSource(): string
    {
        $packagePath = dirname(__FILE__);

        $trace = collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))
            ->first(function ($frame) use ($packagePath) {
                if (!isset($frame['file'])) return false;

                $file = $frame['file'];

                return !str_contains($file, 'vendor') &&
                    !str_starts_with($file, $packagePath);
            });

        return $trace ? "{$trace['file']}:{$trace['line']}" : 'unknown';
    }
}
