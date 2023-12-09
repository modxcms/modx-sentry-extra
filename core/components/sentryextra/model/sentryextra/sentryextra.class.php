<?php

use function Sentry\captureException;

class SentryExtra
{
    public $modx = null;
    public $namespace = 'sentryextra';
    public $options = [];

    public function __construct(modX &$modx, array $options = [])
    {
        $this->modx =& $modx;

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/sentryextra/');

        /* loads some default paths for easier management */
        $this->options = array_merge([
            'namespace' => $this->namespace,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'pluginsPath' => $corePath . 'elements/plugins/',
        ], $options);

        $this->modx->addPackage('sentryextra', $this->getOption('modelPath'));
        $this->modx->lexicon->load('sentryextra:default');
        $this->autoload();
    }

    protected function autoload()
    {
        require_once $this->getOption('corePath') . 'vendor/autoload.php';
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = array(), $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }
        return $option;
    }

    public function log($level, $msg, $file = '', $line = 0): void
    {
        captureException(new \ErrorException($msg, 0, $level, $file, $line));
    }
}
