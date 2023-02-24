<?php
/**
 * @var MODX\Revolution\modX | \modX $modx
 * @var array $scriptProperties
 *
 */
if (empty($modx->version)) {
    $modx->getVersionData();
}
$version = $modx->version['version'] < 3 ? 'v2' : 'v3';

if ($version === 'v2') {
    $sentry = $modx->getService(
        'sentryextra',
        'SentryExtra',
        $modx->getOption(
            'sentryextra.core_path',
            null,
            $modx->getOption('core_path') . 'components/sentryextra/'
        ) . 'model/sentryextra/'
    );
} else {
    $sentry = $modx->services->get('sentryextra');
}

$class = "\\SentryExtra\\$version\\Event\\{$modx->event->name}";
if (class_exists($class)) {
    /** @var \SentryExtra\v2\Event\Event | \SentryExtra\v3\Event\Event  $event */
    $event = new $class($sentry, $scriptProperties);
    $event->run();
}
return;
