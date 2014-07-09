<?php
namespace qeywork;

/**
 * Description of MenuItemCollection
 *
 * @author Dexx
 */
class MenuEntityCollection extends SmartArray implements IRenderable {
    const FIRST = 'first';
    const LAST = 'last';
    const EVEN = 'even';
    
    public $id;
    public $class;
    
    private $visual;

    protected $parent;
    
    public function __construct(IMenuVisual $visual) {
        parent::__construct();
        
        $this->visual = $visual;
    }
    
    public function setParent(IMenuEntity $parent) {
        $this->parent = $parent;
    }
    
    /**
     * @return IMenuEntity
     */
    public function getParent() {
        return $this->parent;
    } 


    public function add(IMenuEntity $item) {
        parent::append($item);
        $item->setParent($this);
    }
    
    public function append($value) {
        $this->add($value);
    }

    public function render() {
        $items = new HtmlEntityList();
        
        $counter = 0;
        foreach ($this as $item) {
            /* @var $item MenuItem */
            $class = null;
            if ($counter == 0) {
                $class[] = self::FIRST;
            }
            if ($counter == $this->count() - 1) {
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
                    $item->getChildren(),
                    $item->getToken(),
                    null,
                    $class,
                    $item->style
                )
            );
            
            ++$counter;
        }
        
        return $this->visual->itemGroup($items, $this->class);
    }

}
