<?php
namespace QeyWork\Auth;

use QeyWork\Entities\Entity;
use QeyWork\Resources\SessionCacheble;

class UserContainer extends SessionCacheble
{
    protected $user = null;
    
    protected function getSessionKey() {
        return 'qey_user';
    }
    
    /**
    * Check if either a user is logged in
    * @return boolean Result
    */
    public function isLoggedIn()
    {
        return $this->user != null;
    }
    
    /**
     * Consrtuctor of this class
     */
    public function login(Entity $user)
    {
        $this->user = $user;
    }
    
    /**
     * Checks if the user currently logged in is an admin
     * @return boolean Result
     */
    public function isAdmin()
    {
        return ($this->isLoggedIn() && $this->user->isAdmin());
    }

    public function getEntity()
    {
        return $this->user;
    }
    
    /**
     * Returns the current IP of the user
     * @return string IP address
     */
    public function getIPofCurrentUser()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
    
    /**
     * Logout the user currently logged in, and return to the home page
     */
    public function logout()
    {
        $this->user = null;
    }
}