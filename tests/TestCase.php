<?php

namespace Maxis\EloquentGuard\Tests;

use Maxis\EloquentGuard\EloquentGuardServiceProvider;
use Maxis\EloquentGuard\QueryMonitor;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [EloquentGuardServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->forgetInstance(QueryMonitor::class);
        $this->app->singleton(QueryMonitor::class, fn() => new QueryMonitor());
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
