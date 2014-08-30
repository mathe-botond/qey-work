<?php
namespace qeywork;

class Cache 
{    
    const CACHE_KEY = ".cache";
    protected $session;
    
    public function __construct(Session $session) {
        $this->session = $session;
    }
    
    public function add($key, &$object)
    {
        if ($this->session->exists(self::CACHE_KEY)) {
            $cache = $this->session->get(self::CACHE_KEY);
        } else {
            $cache = array();
        }
        $cache[$key] = $object;
        $this->session->set(self::CACHE_KEY, $cache);
    }
    
    public function exists($key) {
        if (!$this->session->exists(self::CACHE_KEY)) {
            return false;
        }
        
        $cache = $this->session->get(self::CACHE_KEY);
        
        //Object not in cache
        return isset($cache[$key]);
    }
    
    public function retrieve($key)
    {
        if (!$this->session->exists(self::CACHE_KEY)) {
            return null;
        }
    
        $cache = $this->session->get(self::CACHE_KEY);
        
        //Object not in cache
        if (!isset($cache[$key])) {
            return null;
        }
        
        return $cache[$key];
    }
    
    public function remove($key) {
        if (!$this->session->exists(self::CACHE_KEY)) {
            return;
        }
        
        $cache = $this->session->get(self::CACHE_KEY);
        
        //Object not in cache
        if (!isset($cache[$key])) {
            return null;
        }
        
        unset($cache[$key]);
        $this->session->set(self::CACHE_KEY, $cache);
    }
}
