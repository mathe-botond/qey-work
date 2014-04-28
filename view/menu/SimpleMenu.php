<?php
namespace qeywork;

/**
 * Description of SimpleMenu
 *
 * @author Dexx
 */
class SimpleMenu extends MenuEntity implements IRenderable {
    const FIRST = 'first';
    const LAST = 'last';
    const EVEN = 'even';
    
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
        $this->menuItems = new MenuEntityCollection();
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
    
    public function render() {
        $items = new HtmlEntityList();
        
        $counter = 0;
        foreach ($this->menuItems as $item) {
            /* @var $item MenuItem */
            $class = null;
            if ($counter == 0) {
                $class[] = self::FIRST;
            }
            if ($counter == $this->menuItems->count() - 1) {
                $class[] = self::LAST;
            }
            if ($counter % 2 == 1) { 
                $class[] = self::EVEN;
                //I know it's funny, but it's 0 indexed,
                // thus even items are on odd indexes
            }
            if (! empty($class)) {
                $class = implode(' ', $class);
            }
            
            if ($item->class != null) {
                $class .= ' ' . $item->class;
            }
            
            $items->append(
                $this->visual->item(
                    $item->getLabel(),
                    $item->getLink(),
                    null,
                    $item->name,
                    null,
                    $class,
                    $item->style
                )
            );
            
            ++$counter;
        }
        
        return $this->visual->container($items, $this->id, $this->class);
    }
}
