<?php

namespace Maxis\EloquentGuard;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Maxis\EloquentGuard\Livewire\Pulse\EloquentGuard;

class EloquentGuardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // merge package config with app config
        $this->mergeConfigFrom(__DIR__.'/../config/eloquent-guard.php', 'eloquent-guard');

        // registration of the monitor as a singleton
        $this->app->singleton(QueryMonitor::class, fn() => new QueryMonitor());
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'eloquent-guard');

        if (!config('eloquent-guard.enabled')) {
            return;
        }

        if (class_exists(Livewire::class) && class_exists('\Laravel\Pulse\Facades\Pulse')) {
            Livewire::component('maxis.eloquent-guard', EloquentGuard::class);
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/eloquent-guard.php' => config_path('eloquent-guard.php'),
            ], 'config');
        }

        DB::listen(function (QueryExecuted $query) {
            app(QueryMonitor::class)->handle($query);
        });

    }
}
