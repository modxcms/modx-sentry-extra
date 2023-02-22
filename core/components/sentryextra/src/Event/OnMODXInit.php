<?php

namespace SentryExtra\Event;

class OnMODXInit extends Event
{
    public function run()
    {
        $dsn = $this->getOption('sentryextra.dsn');
        $traces_sample_rate = (float) $this->getOption('sentryextra.traces_sample_rate', null, 0.1);
        if ($traces_sample_rate > 1) {
            $traces_sample_rate = 1;
        }
        if ($traces_sample_rate < 0) {
            $traces_sample_rate = 0;
        }
        if ($dsn) {
            \Sentry\init([
                'dsn' => $dsn,
                'traces_sample_rate' => $traces_sample_rate,
            ]);
        }
    }
}