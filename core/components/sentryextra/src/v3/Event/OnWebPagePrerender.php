<?php

namespace SentryExtra\v3\Event;

class OnWebPagePrerender extends Event
{
    public function run()
    {
        $dsn = $this->getOption('sentryextra.dsn');
        if ($dsn) {
            if (method_exists($this->modx->parser, 'endTransaction')) {
                $this->modx->parser->endTransaction();
            }
        }
    }
}