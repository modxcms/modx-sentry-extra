<?php

namespace SentryExtra\v3;

use ArrayAccess;
use function Sentry\captureException;

class Logger implements ArrayAccess {

    public array $container = [];

    public function __construct($container = []) {
        $this->container = $container;
    }

    public function offsetSet($offset, $value): void {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
        $errmsg = '';
        $errno = 1;
        $errfile = null;
        $errline = null;
        if (is_array($value)) {
            $errmsg = $value['msg'] ?? '';
            $errno = (int) ($value['level'] ?? 1);
            $errfile = $value['file'] ?? null;
            $errline = $value['line'] ? (int) $value['line'] : null;
        } elseif (is_string($value)) {
            $errmsg = $value;
        }

        captureException(new \ErrorException($errmsg, 0, $errno, $errfile, $errline));
    }

    public function offsetExists($offset): bool {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset): void {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset) {
        return $this->container[$offset] ?? null;
    }
}