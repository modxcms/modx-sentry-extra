<?php

namespace SentryExtra\v2\Event;

class OnWebPageInit extends Event
{
    public function run()
    {
        $dsn = $this->getOption('sentryextra.dsn');
        if ($dsn) {
            if (method_exists($this->modx->parser, 'endTransaction')) {
                $this->modx->parser->startTransaction();
            }
        }
    }
}