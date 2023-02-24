<?php
namespace SentryExtra\v3\Event;

use SentryExtra\v3\SentryExtra;

abstract class Event
{
    /** @var SentryExtra */
    protected $sentryExtra;

    /** @var \MODX\Revolution\modX */
    protected $modx;

    /** @var array */
    protected $sp = [];

    public function __construct(SentryExtra $sentryExtra, array $scriptProperties)
    {
        $this->sentryExtra =& $sentryExtra;
        $this->modx =& $this->sentryExtra->modx;
        $this->sp = $scriptProperties;
    }

    abstract public function run();

    protected function getOption($key, $default = null, $skipEmpty = false)
    {
        return $this->modx->getOption($key, $this->sp, $default, $skipEmpty);
    }
}