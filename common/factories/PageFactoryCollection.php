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
        
        if ($target == $this->defaultPage) {
            $type = Page::FRONT_PAGE;
        } else {
            $type = Page::NO_SPECIAL_TYPE;
        }
        
        foreach ($this->collection as $factory) {
            /* @var $factory IPageFactory */
            $page = $factory->getPage($target);
            if ($page != null) {
                $page->setType($type);
                return $page;
            }
        }
        if ($page == null) {
            throw new ClientDataException("No page found named '$target'");
        }
    }
}
