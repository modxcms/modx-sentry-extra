<?php

namespace SentryExtra\v3\Event;

use SentryExtra\v3\Parser;

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