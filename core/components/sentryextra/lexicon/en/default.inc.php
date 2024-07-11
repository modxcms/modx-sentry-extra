<?php
$_lang['sentryextra'] = 'Sentry';
$_lang['sentryextra.desc'] = 'Sentry Extra for MODX Revolution';

$_lang['setting_sentryextra.dsn'] = 'Sentry DSN';
$_lang['setting_sentryextra.dsn_desc'] = 'The DSN tells the SDK where to send the events to. If this value is not provided, the SDK will just not send any events.';
$_lang['setting_sentryextra.environment'] = 'Sentry Environment';
$_lang['setting_sentryextra.environment_desc'] = 'The environment of the application you are monitoring with Sentry. This string is freeform and not set by default. Commonly used values are: production, staging, development, etc.';
$_lang['setting_sentryextra.traces_sample_rate'] = 'Sentry Traces Sample Rate';
$_lang['setting_sentryextra.traces_sample_rate_desc'] = 'The percentage chance of transactions being sent to Sentry. (So, for example, if you set traces_sample_rate to 0.2, approximately 20% of your transactions will get recorded and sent.)';
$_lang['setting_sentryextra.keep_error_log'] = 'Keep Error Log';
$_lang['setting_sentryextra.keep_error_log_desc'] = 'Keep PHP errors in the log or only send to Sentry. Standard $modx->log() events will still be recorded.';
$_lang['setting_sentryextra.errors_only'] = 'Ignore Notices and Warnings';
$_lang['setting_sentryextra.errors_only_desc'] = 'Only log PHP errors, not warnings or deprecation notices.';