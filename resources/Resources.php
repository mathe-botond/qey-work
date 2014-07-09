<?php
namespace qeywork;

/**
 * Description of Resources
 *
 * @author Dexx
 */
abstract class Resources {
    /** @var DB */
    protected $db;
    /** @var History */
    protected $history;
    /** @var Params */
    protected $params;
    /** @var Session */ 
    protected $session;
    /** @var Cache */
    protected $cache;
    /** @var Localization */
    protected $localization;
    /** @var Locations */
    protected $locations;
    
    public function __construct(Locations $locations) {
        $this->locations = $locations;
    }
    
    /**
     * @return DB database
     */
    protected abstract function initializeDatabase();
    
    /**
     * @return History history
     */
    protected abstract function initializeHistory(Cache $cache);
    
    /**
     * @return Params params
     */
    protected abstract function initializeParams();
    
    /**
     * @return Cache cache
     */
    protected abstract function initializeCache();
    
    /**
     * @return Localization localization
     */
    protected abstract function initializeLocalization();
    
    /**
     * @return Session session
     */
    protected abstract function initializeSession();
    
    protected abstract function cacheNecesarryClasses(Cache $cache);
    
    /**
     * @return DB
     */
    public function getDb() {
        if ($this->db == null) {
            $this->db = $this->initializeDatabase();
        }
        
        return $this->db;
    }
    
    /**
     * @return History
     */
    public function getHistory() {
        if ($this->history == null) {
            $cache = $this->getCache();
            $this->history = $this->initializeHistory($cache);
        }
        
        return $this->history;
    }
    
    /**
     * @return Params
     */
    public function getParams() {
        if ($this->params == null) {
            $this->params = $this->initializeParams();
        }
        
        return $this->params;
    }
    
    /**
     * @return Session
     */
    public function getSession() {
        if ($this->session == null) {
            $this->session = $this->initializeSession();
        }
        
        return $this->session;
    }
    
    /**
     * @return Cache
     */
    public function getCache() {
        if ($this->cache == null) {
            $this->cache = $this->initializeCache();
        }
        
        return $this->cache;
    }
    
    /**
     * @return Localization
     */
    public function getLocalization() {
        if ($this->localization == null) {
            $this->localization = $this->initializeLocalization();
        }
        
        return $this->localization;
    }
    
    /**
     * @return Locations
     */
    public function getLocations() {
        return $this->locations;
    }
    
    public function __destruct() {
        $cache = $this->getCache();
        $this->cacheNecesarryClasses($cache);
    }
}
