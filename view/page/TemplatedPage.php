<?php
namespace qeywork;

/**
 * Default implementation of the IBasicPage interface
 * @author Dexx
 */
abstract class TemplatedPage extends TemplatedBlock implements IPage
{   
    public function setType($type) {
        $this->type = $type;
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
