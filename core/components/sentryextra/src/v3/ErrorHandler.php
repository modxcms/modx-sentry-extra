<?php
namespace SentryExtra\v3;

use MODX\Revolution\Error\modErrorHandler;
use function Sentry\captureException;

class ErrorHandler extends modErrorHandler
{
    /**
     * Handles any recoverable PHP errors or calls to trigger_error().
     *
     * @param integer $errno An integer number indicating the type of error.
     * @param string $errstr A description of the error.
     * @param string $errfile The filename in which the error occured.
     * @param integer $errline The line number in the file where the error occured.
     * @param array $errcontext A copy of all variables and their values available at the time the
     * error occured and in the scope of the script being executed.
     * @return boolean True if the error was handled or false if the default PHP error handler
     * should be invoked to handle it.
     */
    public function handleError($errno, $errstr, $errfile = null, $errline = null, $errcontext = null)
    {
        if (error_reporting() == 0) {
            return;
        }
        $handled = true;
        switch ($errno) {
            case E_USER_ERROR:
                $errmsg= 'User error: ' . $errstr;
                break;
            case E_WARNING:
                $errmsg= 'PHP warning: ' . $errstr;
                break;
            case E_USER_WARNING:
                $errmsg= 'User warning: ' . $errstr;
                break;
            case E_NOTICE:
                $errmsg= 'PHP notice: ' . $errstr;
                break;
            case E_USER_NOTICE:
                $errmsg= 'User notice: ' . $errstr;
                break;
            case E_STRICT:
                $errmsg= 'E_STRICT information: ' . $errstr;
                break;
            case E_RECOVERABLE_ERROR:
                $errmsg= 'Recoverable error: ' . $errstr;
                break;
            case E_DEPRECATED:
                $errmsg= 'PHP deprecated: ' . $errstr;
                break;
            case E_USER_DEPRECATED:
                $errmsg= 'User deprecated: ' . $errstr;
                break;
            default:
                $handled = false;
                $errmsg= 'Un-recoverable error ' . $errno . ': '. $errstr;
                break;
        }
        captureException(new \ErrorException($errmsg, 0, $errno, $errfile, $errline));
        if ($this->modx->getOption('sentryextra.keep_error_log', null, true)) {
            return parent::handleError($errno, $errstr, $errfile, $errline, $errcontext);
        }
        return $handled;
    }

    public function handleException(\Throwable $e)
    {
        captureException($e);
    }

    public function handleShutdown()
    {
        $error = error_get_last();
        if ($error && $error['type'] === E_ERROR) {
            $this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
            die();
        }
    }
}
