# Sentry Extra for MODX Revolution
This is a MODX Extra that provides a [Sentry](https://sentry.io) integration for MODX Revolution.

## Installation
1. Install the extra via the MODX Package Manager
2. Create a new project in Sentry
3. Copy the DSN from the project settings
4. Go to the System Settings and set the `sentry.dsn` setting to the DSN you copied in step 3
5. Go to the System Settings and set the `sentry.environment` setting to the environment you want to use (e.g. `production`)

## Usage
The extra will automatically log all errors to Sentry. You can also log messages manually:

### MODX 2.x
```php
$sentry = $modx->getService('sentryextra', 'SentryExtra', $modx->getOption('sentryextra.core_path', null, $modx->getOption('core_path') . 'components/sentryextra/') . 'model/sentryextra/');
if ($sentry) $sentry->log(xPDO::LOG_LEVEL_ERROR, 'This is a test error message');
```

### MODX 3.x
```php
$sentry = $modx->services->get('sentryextra');
if ($sentry) $sentry->log(xPDO::LOG_LEVEL_ERROR, 'This is a test error message');
```

## Additional System Settings
- `sentry.traces_sample_rate` (default: `0.1`) - The percentage of errors that should be sent to Sentry. This is useful for testing the integration without spamming Sentry with errors (So, for example, if you set traces_sample_rate to 0.2, approximately 20% of your transactions will get recorded and sent.).
- `sentry.keep_error_log` (default: `1`) - If set to `1`, the PHP Errors will not be cleared after sending the errors to Sentry. This has no effect on events that call `$modx->log()` directly.
