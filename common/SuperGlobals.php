<?php
namespace qeywork;

/**
 * @author Dexx
 */
class SuperGlobals {
    const KEY_SESSION = 'session';
    const KEY_GET = 'get';
    const KEY_POST = 'post';
    const KEY_REQUEST = 'request';
    const KEY_SERVER = 'server';
    
    private $superGlobals;
    
    private function initSuperGlobals() {
        $this->superGlobals = array(
            'session' => null,
            'get' => $_GET,
            'post' => $_POST,
            'request' => $_REQUEST,
            'server' => $_SERVER
        );
    }
    
    public function __construct() {
        $this->initSuperGlobals();
    }

    private function validateKey($key) {
        if (!array_key_exists($key, $this->superGlobals)) {
            throw new ArgumentException("Key must be the name of an existing superglobal");
        }
    }
    
    public function swapSuperGlobalWith($key, array $global) {
        $this->validateKey($key);
        $this->superGlobals[$key] = $global;
    }
    
    public function getSuperGlobal($key) {
        $this->validateKey($key);
        return $this->superGlobals[$key];
    }
    
    public function getServer() {
        return $this->superGlobals[SuperGlobals::KEY_SERVER];
    }
}
