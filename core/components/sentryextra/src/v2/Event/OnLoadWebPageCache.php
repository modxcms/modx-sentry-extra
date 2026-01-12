<?php

namespace SentryExtra\v2\Event;

use function Sentry\captureException;

class OnLoadWebPageCache extends Event
{
    public function run()
    {
        $dsn = $this->getOption('sentryextra.dsn');
        if ($dsn) {
            if (method_exists($this->modx->parser, 'endTransaction')) {
                $this->modx->parser->endTransaction();
            }
            if (!$this->modx->getOption('sentryextra.keep_error_log', true)) {
                $logTarget = $this->modx->getLogTarget();
                if (is_array($logTarget) && $logTarget['target'] === 'ARRAY_EXTENDED') {
                    $logger = & $logTarget['options']['var'];
                    if (is_array($logger)) {
                        foreach ($logger as $entry) {
                            $errmsg = '';
                            $errno = 1;
                            $errfile = null;
                            $errline = null;
                            if (is_array($entry)) {
                                $errmsg = $entry['msg'] ?? '';
                                $errno = $entry['level'] ?? 1;
                                $errfile = $entry['file'] ?? null;
                                $errline = $entry['line'] ?? null;
                            } elseif (is_string($entry)) {
                                $errmsg = $entry;
                            }

                            captureException(new \ErrorException($errmsg, 0, $errno, $errfile, $errline));
                        }
                    }
                }
            }
        }
    }
}