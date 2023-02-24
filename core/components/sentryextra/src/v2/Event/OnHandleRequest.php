<?php

namespace SentryExtra\v2\Event;

use SentryExtra\v2\Parser;

class OnHandleRequest extends Event
{
    public function run()
    {
        $dsn = $this->getOption('sentryextra.dsn');
        if ($dsn) {
            $this->modx->parser = new Parser($this->modx);
        }
    }
}