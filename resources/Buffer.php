<?php
namespace QeyWork\Resources;
use BadMethodCallException;
use OutOfRangeException;

/**
 * Buffer wrapper
 */
class Buffer
{
    //private static $opened = false;
    private static $level = 0;
    
    /**
     * Starts output buffering
     * @throws BadMethodCallException when trying to start twice
     */
    public function start()
    {
        ++self::$level;
        //$backtrace = debug_backtrace();
        //getLogger()->debug(self::$level . ': ' . $backtrace[0]['file'] . ' ' . $backtrace[0]['line']);
        
        //if (! self::$opened) {
            ob_start();
        //    self::$opened = true;
        //} else {
        //    throw new BadMethodCallException('Buffer is already open');
        //}
    }
    
    /**
     * Flushes output buffer and might close it if requested
     * @param bool $finish when true the buffer is closed
     * @return string buffer content
     * @throws BadMethodCallException when trying to close a buffer
     *      already closed 
     */
    public function flush($finish = true)
    {
        //if (self::$opened) {
            $data = ob_get_contents();
            if ($finish) {
                ob_end_clean();
                
                --self::$level;
                if (self::$level < 0) {
                    throw new OutOfRangeException('Buffer closed too many times');
                }
                //$backtrace = debug_backtrace();
                //getLogger()->debug(self::$level . ': ' . $backtrace[1]['file'] . ' ' . $backtrace[1]['line']);
                      
                //self::$opened = false;
            } else {
                ob_clean();
            }
        
            return $data;
        //} else {
        //    throw new BadMethodCallException('Buffer wasn\'t started');
        //}
    }
    
    public function getLevel() {
        return self::$level;
    }
}
