<?php

use Maxis\EloquentGuard\Reporters\LogReporter;
use Maxis\EloquentGuard\Reporters\SentryReporter;
use Maxis\EloquentGuard\Reporters\SlackReporter;
use Maxis\EloquentGuard\Reporters\TelegramReporter;

return [
    'enabled' => env('ELOQUENT_GUARD_ENABLED', true),

    // limits for alerts
    'limits' => [
        'queries_per_request' => 50,    // max requests per page
        'query_duration_ms' => 500,   // max query duration (ms)
        'n_plus_one_threshold' => 5,     // max N+1 queries
    ],

    // tables to ignore
    'except_tables' => [
        'cache', 'sessions', 'pulse_entries', 'pulse_values', 'jobs', 'migrations'
    ],

    // slack webhook url
    'slack_webhook_url' => env('ELOQUENT_GUARD_SLACK_WEBHOOK'),

    // telegram options
    'telegram' => [
        'token' => env('ELOQUENT_GUARD_TELEGRAM_TOKEN'),
        'chat_id' => env('ELOQUENT_GUARD_TELEGRAM_CHAT_ID'),
    ],

    // where to send alerts
    'reporters' => [
        LogReporter::class,
//        SlackReporter::class,
//        TelegramReporter::class,
//        SentryReporter::class,
    ],
];
