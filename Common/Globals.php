<?php
namespace QeyWork\Common;

/**
 * @author Dexx
 */
class Globals {
    const KEY_SESSION = 'session';
    const KEY_GET = 'get';
    const KEY_POST = 'post';
    const KEY_REQUEST = 'request';
    const KEY_SERVER = 'server';
    
    protected $globals;
    
    private function initSuperGlobals() {
        $this->globals = array(
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
        if (!array_key_exists($key, $this->globals)) {
            throw new ArgumentException("Key must be the name of an existing superglobal");
        }
    }
    
    public function swapSuperGlobalWith($key, array $global) {
        $this->validateKey($key);
        $this->globals[$key] = $global;
    }
    
    public function getGlobal($key) {
        $this->validateKey($key);
        return $this->globals[$key];
    }
    
    public function getServer() {
        return $this->globals[Globals::KEY_SERVER];
    }
}
