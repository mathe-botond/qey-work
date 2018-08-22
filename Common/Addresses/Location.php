<?php
namespace QeyWork\Common\Addresses;

/**
 * @author Dexx
 */
class Location {

    /**
     * @var Url
     */
    private $remote;

    /**
     * @var Path
     */
    private $local;

    public function __construct(Path $local, Url $remote) {        
        $this->local = $local;
        $this->remote = $remote;
    }
    
    public function getLocal() {
        return $this->local;
    }
    
    public function getRemote() {
        return $this->remote;
    }
}
