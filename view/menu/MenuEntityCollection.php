<?php
namespace qeywork;

/**
 * Description of MenuItemCollection
 *
 * @author Dexx
 */
class MenuEntityCollection extends SmartArrayObject {
    public $id;
    public $class;
    
    public function add(MenuEntity $item) {
        parent::append($item);
        $item->setParent($this);
    }
}

?>
