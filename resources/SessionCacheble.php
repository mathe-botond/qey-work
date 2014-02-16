<?php
namespace qeywork;

/**
 * @author Dexx
 */
abstract class SessionCacheble {
    public $session;
    
    protected abstract function getSessionKey();
    
    public function __construct(Session $session) {
        $key = $this->getSessionKey();
        if ($session->exists($key)) {
            $object = $session->get($key);
            foreach ($object as $parameter => $value) {
                $this->$parameter = $value;
            }
        }        
        $this->session = $session;
    }
    
    public function __destruct() {
        $obj = new \stdClass();
        foreach ($this as $key => $param) {
            if ($key !== 'session') {
                $obj->$key = $param;
            }
        }

        $this->session->set($this->getSessionKey(), $obj);
    }
}
?>
