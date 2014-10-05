<?php
namespace qeywork;

/**
 * @author Dexx
 */
class PageRouter implements IPageRouter {
    /** @var array */
    private $pages;
    
    public function __construct() {
        $this->pages = array();
    }
    
    public function addPageClass($token, $className) {
        $this->pages[$token] = $className;
    }
    
    public function getPage(Arguments $target) {
        $sTarget = $target->toString();
        if (array_key_exists($sTarget, $this->pages)) {
            return $this->pages[$sTarget];
        } else {
            return null;
        }
    }
}
