<?php

namespace SentryExtra\v2\Event;

use SentryExtra\v2\ErrorHandler;
use function Sentry\init;

class OnMODXInit extends Event
{
    public function run()
    {
        $dsn = $this->getOption('sentryextra.dsn');
        $environment = $this->getOption('sentryextra.environment', 'production');
        $traces_sample_rate = (float) $this->getOption('sentryextra.traces_sample_rate', null, 0.1);
        if ($traces_sample_rate > 1) {
            $traces_sample_rate = 1;
        }
        if ($traces_sample_rate < 0) {
            $traces_sample_rate = 0;
        }
        if ($dsn) {
            init([
                'dsn' => $dsn,
                'traces_sample_rate' => $traces_sample_rate,
                'environment' => $environment,
            ]);
            $this->modx->errorHandler = new ErrorHandler($this->modx, []);
            set_error_handler(
                array($this->modx->errorHandler, 'handleError'),
                $this->getOption('error_handler_types', error_reporting(), true)
            );
            set_exception_handler(array($this->modx->errorHandler, 'handleException'));
            register_shutdown_function(array($this->modx->errorHandler, 'handleShutdown'));
        }
    }
}
