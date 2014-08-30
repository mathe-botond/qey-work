<?php
namespace qeywork;

/**
 * @author Dexx
 */
class NullLogger extends Logger {
    public function __construct() {
    }
    
    public function log($line, $priority) {
        //parent::log($line, $priority);
    }
}
