<?php
namespace QeyWork\Common;

/**
 * @author Dexx
 */
class Friendly {
    private $__friends;
    
    public function __construct(array $friends) {
        $this->__friends = $friends;
    }

    public function __get($key)
    {
        $trace = debug_backtrace();
        $class = $trace[1]['class'];
        if(isset($class) && in_array($class, $this->__friends)) {
            return $this->$key;
        }

        // normal __get() code here

        trigger_error("Property '$class::$key' not found or private", E_USER_ERROR);
    }

    public function __set($key, $value)
    {
        $trace = debug_backtrace();
        if(isset($trace[1]['class']) && in_array($trace[1]['class'], $this->__gang)) {
            return $this->$key = $value;
        }

        trigger_error("Property '" . __CLASS__ . "::$key' not found or private", E_USER_ERROR);
    }
}
