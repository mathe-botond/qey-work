<?php
namespace qeywork;

/**
 * Description of SimpleMenu
 *
 * @author Dexx
 */
class Menu  implements IMenuEntity, IRenderable {
    
    /** @var MenuEntityCollection */
    protected $menuItems;
    /** @var IMenuVisual */
    protected $visual;
    
    /** @var string */
    public $id;
    /** @var string */
    public $class;
    /** @var string */
    public $childClass;
    
    public function __construct(IMenuVisual $visual) {
        $this->menuItems = new MenuEntityCollection($visual);
        $this->visual = $visual;
    }

    public function add(MenuItem $item) {
        if ($this->childClass != '') {
            if (! empty($item->class)) {
                $item->class .= ' ';
            }
            $item->class .= $this->childClass;
        }
        $this->menuItems->add($item);
    }
    
    public function getChildren() {
        return $this->menuItems;
    }
    
    public function render() {
        $items = $this->menuItems->render();
        return $this->visual->menu($items, $this->id, $this->class);
    }
    
    public function setActiveItem(MenuItem $item) {
        $item->class .= ' active';
        while (null != ($parent = $item->getParent())) {
            $item = $parent;
            $item->class .= ' active-trail';
        }
    }
}
