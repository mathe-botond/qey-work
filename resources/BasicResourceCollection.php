<?php
namespace qeywork;

/**
 * Description of ResourceProvider
 *
 * @author Dexx
 */
class BasicResourceCollection extends ResourceCollection {
    /** @var DBConfig */
    protected $dbConfing;
    /** @var Locations */
    protected $locations;
    /** @var string */
    protected $appName;
    /** @var string */
    protected $logFile = null;
    
    /** @var Logger */
    protected $logger;
    
    const HISTORY = 'History';
    
    /**
     * @param DBConfig $dbConfig
     * @param Locations $locations
     * @param string $appName
     */
    public function __construct(DBConfig $dbConfig,
            Locations $locations,
            $appName) {
        parent::__construct($locations);
        $this->dbConfing = $dbConfig;
        $this->appName = $appName;
    }
    
    /**
     * @param Path $logFile
     */
    public function useLoggingForResources($logFile) {
        $this->logFile = $logFile;
    }
    
    protected function isLoggingUsed() {
        return $this->logFile !== null;
    }
    
    protected function initializeCache() {
        $session = $this->getSession();
        return new Cache($session);
    }

    protected function initializeDatabase() {
        if ($this->isLoggingUsed()) {
            $logger = $this->getLogger();
        } else {
            $logger = null;
        }
        return new DB($this->dbConfing, $logger);
    }

    protected function initializeHistory(Cache $cache) {
        $history = $cache->retrieve(self::HISTORY);
        if ($history == null) {
            $history = new History($this->locations->homePage);
        }
        return $history;
    }

    protected function initializeLocalization() {
        return new Localization();
    }

    protected function initializeParams() {
        return new Params();
    }

    protected function initializeSession() {
        return new Session($this->appName);
    }
    
    protected function initializeLogger($logFile) {
        return new Logger($logFile);
    }

    protected function cacheNecesarryClasses(Cache $cache) {
        if ($this->history !== null) {
            $cache->add(self::HISTORY, $this->history);
        }
    }
    
    /**
     * BasicResourceCollection uses Location class to initialize the Logger
     * @return Logger
     */
    public function getLogger() {
        if ($this->logger == null) {
            if (! $this->isLoggingUsed()) {
                throw new DependencyException('Logging is not used. ' .
                        'Call BasicResourceCollection::useLoggingForResources first '.
                        'to provide Logger dependencies.');
            }
            $this->logger = $this->initializeLogger($this->logFile);
        }
        
        return $this->logger;
    }
}
