<?php

namespace SentryExtra\Event;

class OnHandleRequest extends Event
{
    public function run()
    {
        $dsn = $this->getOption('sentryextra.dsn');
        if ($dsn) {
            $this->modx->parser = new \SentryExtra\Parser($this->modx);
        }
    }
}