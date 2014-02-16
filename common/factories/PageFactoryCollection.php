<?php
namespace qeywork;

class PageFactoryCollection {
    
    /** @var array */
    private $collection;
    
    protected $defaultPage;
    
    public function __construct($defaultPage) {
        $this->defaultPage = $defaultPage;
        $this->collection = array();
    }
    
    public function addFactory(IPageFactory $factory) {
        array_unshift($this->collection, $factory);
    }
    
    public function getCurrentPage($target) {
        if ($target == '') {
            $target = $this->defaultPage;
        }
        
        foreach ($this->collection as $factory) {
            /* @var $factory IPageFactory */
            $page = $factory->getPage($target);
            if ($page != null) {
                return $page;
            }
        }
        if ($page == null) {
            throw new ClientDataException("No page found named '$target'");
        }
    }
}
?>
