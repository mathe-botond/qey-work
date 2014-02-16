<?php
namespace qeywork;

class Session
{
    protected $baseKey = null;
    
    /**
     * @param string $appName
     */
    public function __construct($appName) {
        session_start();
        $this->baseKey = $appName;
        if (! isset($_SESSION[$this->baseKey])) {
            $_SESSION[$this->baseKey] = array();
        }
    }
    
    static function start()
    {
        session_start();
    }
 
    function &get($key) {
        if(isset($_SESSION[$this->baseKey][$key])) {
            return $_SESSION[$this->baseKey][$key];
        } else {
            throw new ArgumentException("Session variable ".$key." does no exist");
        }
    }
    
    function set($key, $value) {
        $_SESSION[$this->baseKey][$key] = $value;
    }
    
    function __set($key, $value)
    {
        self::set($key, $value);
    }
 
    function &__get($key)
    {
        return self::get($key);
    }
    
    function exists($key)
    {
        return isset($_SESSION[$this->baseKey][$key]);
    }
 
    function del($key)
    {
        unset($_SESSION[$this->baseKey][$key]);
    }
 
    static function destroy()
    {
        $_SESSION[$this->baseKey] = array();
        session_destroy();
    }
}
?>