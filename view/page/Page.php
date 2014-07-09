<?php
namespace qeywork;

/**
 * Default implementation of the IBasicPage interface
 * @author Dexx
 */
abstract class Page implements IPage, IPageByToken {
    
    private $type = IPage::NO_SPECIAL_TYPE;
    private $token;

    public function setType($type) {
        $this->type = $type;
    }
    
    public function setToken($token) {
        $this->token = $token;
    }
    
    public function getToken() {
        return $this->token;
    }
    
    /**
     * Generates title from class name
     * @return string Title 
     */
    public function getTitle() {
        return null;
    }
    
    public function isFrontPage() {
        return ($this->type & self::FRONT_PAGE) == 1;
    }
}
