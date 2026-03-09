<?php

namespace Maxis\EloquentGuard\Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EloquentGuardTest extends TestCase
{
    public function test_it_detects_n_plus_one_queries()
    {
        config(['eloquent-guard.enabled' => true]);
        config(['eloquent-guard.limits.n_plus_one_threshold' => 2]);

        Log::spy();

        DB::select('SELECT 1');
        DB::select('SELECT 1');

        Log::shouldHaveReceived('warning')
            ->once()
            ->withArgs(fn($message) => str_contains($message, 'N+1 detected'));

        $this->assertTrue(true);
    }

    public function test_it_ignores_different_queries()
    {
        config(['eloquent-guard.enabled' => true]);
        config(['eloquent-guard.limits.n_plus_one_threshold' => 5]);

        Log::spy();

        DB::select('SELECT 1');
        DB::select('SELECT 2');

        Log::shouldNotHaveReceived('warning');

        $this->assertTrue(true);
    }
}
