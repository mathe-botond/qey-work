<?php
namespace qeywork;

/**
 * @author Dexx
 */
class NullResourceCollection extends NullResourceCollection {
    public function __construct() {
        ;
    }
    
    protected function cacheNecesarryClasses(Cache $cache) {
        
    }

    protected function initializeCache() {
        
    }

    protected function initializeDatabase() {
        
    }

    protected function initializeHistory(Cache $cache) {
        
    }

    protected function initializeLocalization() {
        
    }

    protected function initializeParams() {
        return new Params();
    }

    protected function initializeSession() {
        return new Session('null');
    }
}
