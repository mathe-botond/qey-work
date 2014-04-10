<?php
namespace qeywork;

/**
 * Description of MenuEntity
 *
 * @author Dexx
 */
class MenuEntity {
    /** @var MenuEntity parent */
    private $parent;
    private $level;
    
    public function setParent(MenuEntityCollection $parent) {
        $this->parent = $parent;
    }   
}
