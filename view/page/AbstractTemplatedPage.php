<?php
namespace qeywork;

/**
 * Default implementation of the IBasicPage interface
 * @author Dexx
 */
abstract class AbstractTemplatedPage extends AbstractTemplatedBlock implements IPage
{   
    private $type;
    
    public function setType($type) {
        $this->type = $type;
    }

    public function isType($type) {
        return $this->type & $type == 1;
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
