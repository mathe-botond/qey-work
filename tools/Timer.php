<?php
namespace qeywork;

/**
 * Helper class to measure time 
 */
class Timer extends ControllerEntity {
    protected $measured;
    protected $start;
    protected $state;
    
    public function __construct() {
        $this->reset();
    }
    
    /**
    * @return Timer the default singleton timer 
    */
    public static function getDefaultTimer() {
        return Timer::getInstance();
    }
    
    public function reset() {
        $this->measured = 0;
        $this->start = null;
    }
    
    public function start() {
        $this->start = microtime(true);
    }
    
    public function get($digits = 4) {
        if ($this->start === null) {
            throw new BadFunctionCallException('Timer was never started');
        }
        return number_format(( microtime(true) - $this->start), $digits);
    }
}
?>