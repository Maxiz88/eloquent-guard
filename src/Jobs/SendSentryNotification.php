<?php

namespace Maxis\EloquentGuard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sentry\State\Scope;
use function Sentry\captureMessage;
use function Sentry\withScope;

class SendSentryNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $message,
        protected string $sql,
        protected string $source,
        protected array  $data
    )
    {
    }

    public function handle(): void
    {
        withScope(function (Scope $scope) {
            $scope->setExtra('source', $this->source);
            $scope->setExtra('sql', $this->sql);
            $scope->setContext('query_details', $this->data);
            $scope->setTag('package', 'laravel-eloquent-guard');
            $scope->setLevel(\Sentry\Severity::warning());

            captureMessage($this->message);
        });
    }
}
