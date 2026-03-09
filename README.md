# Eloquent Guard for Laravel 🛡️

[![Latest Version on Packagist](https://img.shields.io)](https://packagist.org)
[![Total Downloads](https://img.shields.io)](https://packagist.org)

**Eloquent Guard** is a lightweight, production-ready runtime performance monitor for Laravel. It detects N+1 queries and slow database operations as they happen, helping you keep your application fast and efficient.

## ✨ Features

- **N+1 Query Detection**: Automatically detects repeated queries within a single request.
- **Slow Query Monitor**: Alerts you when a query exceeds your defined time limit.
- **Laravel Pulse Integration**: Beautiful custom dashboard card to visualize alerts.
- **Smart Backtrace**: Pinpoints the exact file and line of code causing the issue (skips vendor files).
- **Multi-channel Reporting**: Built-in support for Log, Slack, Telegram, and Sentry.
- **Zero Configuration**: Works out of the box with sensible defaults.

## 🚀 Installation

You can install the package via composer:

```bash
composer require maxis/eloquent-guard
```
## Usage
### Publish Configuration
Publish the configuration file to customize thresholds, ignored tables, and active reporters:
```bash
php artisan vendor:publish --provider="Maxis\EloquentGuard\EloquentGuardServiceProvider" --tag="config"
```
### Configure Environment
Add your webhook URLs or bot tokens to your .env file for instant alerts:
```env
ELOQUENT_GUARD_ENABLED=true
ELOQUENT_GUARD_SLACK_WEBHOOK=https://hooks.slack.com...
ELOQUENT_GUARD_TELEGRAM_TOKEN=your-bot-token
ELOQUENT_GUARD_TELEGRAM_CHAT_ID=your-chat-id
```
### ⚙️ Configuration
Once published, you can find the settings in config/eloquent-guard.php.
#### Thresholds & Limits
Customize what constitutes a performance issue for your specific application:
```php
'limits' => [
    'n_plus_one_threshold' => 5,   // Alert after 5 identical queries in one request
    'query_duration_ms' => 500,    // Alert if a single query takes more than 500ms
],
```
#### Reporters
Enable or disable notification channels by adding/removing classes from the reporters array:
```php
'reporters' => [
    \Maxis\EloquentGuard\Reporters\LogReporter::class,      // Standard Laravel Logs
    \Maxis\EloquentGuard\Reporters\SlackReporter::class,    // Slack Webhooks (Queue supported)
    \Maxis\EloquentGuard\Reporters\TelegramReporter::class, // Telegram Bot API
    \Maxis\EloquentGuard\Reporters\SentryReporter::class,   // Sentry Issue Tracking
],
```
#### Ignored Tables
Prevent noise from system tables (like sessions or cache):
```php
'except_tables' => ['sessions', 'cache', 'pulse_entries', 'jobs', 'migrations'],
```
### 📊 Laravel Pulse Integration
Eloquent Guard comes with a dedicated Laravel Pulse card to visualize N+1 and Slow Query trends directly in your dashboard.
#### Add the Card to Dashboard
Include the Livewire component in your resources/views/vendor/pulse/dashboard.blade.php:
```html
<x-pulse>
    <!-- Display Eloquent Guard Alerts -->
    <livewire:maxis.eloquent-guard cols="4" />

    <!-- Existing Pulse cards... -->
</x-pulse>

```
### 🧪 Testing
The package is fully tested with PHPUnit. If you are developing locally or using Laravel Sail, run the test suite with:
```bash
# Using Laravel Sail
sail php ./packages/maxis/eloquent-guard/vendor/bin/phpunit -c ./packages/maxis/eloquent-guard/phpunit.xml

# Or standard PHPUnit from the package directory
./vendor/bin/phpunit
```
### 🤝 Contributing
Contributions make the open-source community amazing!

1. **Fork** the Project
2. **Create** your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. **Commit** your Changes (`git commit -m 'Add some AmazingFeature'`)
4. **Push** to the Branch (`git push origin feature/AmazingFeature`)
5. **Open** a Pull Request

### 📄 License
Distributed under the MIT License. See LICENSE.md for more information.
