<?php
/**
 * @var modX $modx
 * @var array $scriptProperties
 */
$sentry = $modx->getService('sentryextra', 'SentryExtra', $modx->getOption('sentryextra.core_path', null, $modx->getOption('core_path') . 'components/sentryextra/') . 'model/sentryextra/');
if (!($sentry instanceof \SentryExtra)) return '';

$class = "\\SentryExtra\\Event\\{$modx->event->name}";
if (class_exists($class)) {
    /** @var \SentryExtra\Event\Event $event */
    $event = new $class($sentry, $scriptProperties);
    $event->run();
}
return;