<?php
namespace qeywork;

class Session
{
    /**
     * @param string $appName
     */
    public function __construct($appName) {
        if(session_id() == '') {
            session_name($appName);
            session_start();
        }
    }
 
    function &get($key) {
        if(isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            throw new ArgumentException("Session variable ".$key." does no exist");
        }
    }
    
    function set($key, $value) {
        $_SESSION[$key] = $value;
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
        return isset($_SESSION[$key]);
    }
 
    function del($key)
    {
        unset($_SESSION[$key]);
    }
 
    public function destroy()
    {
        $_SESSION = array();
        session_destroy();
    }
}
