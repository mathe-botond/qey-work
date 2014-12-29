<?php
namespace qeywork;

class PageRouteCollection {
    const INDEX_TOKEN = 'index';
    
    private $defaultRouter;
    private $routers = array();

    public function __construct($indexPageClass) {
        $this->defaultRouter = new PageRouter();
        $this->defaultRouter->addPageClass(self::INDEX_TOKEN, $indexPageClass);
    }
    
    public function addPageClass($token, $className) {
        $this->defaultRouter->addPageClass($token, $className);
    }
    
    public function addRouter(IPageRouter $router) {
        $this->routers[] = $router;
    }
    
    public function getCurrentPage(Arguments $target) {
        if (trim($target->toString()) == '') {
            $target->forceOtherToken(self::INDEX_TOKEN);
        }
        
        $page = $this->defaultRouter->getPage($target);
        if ($page != null) {
            return $page;
        }
        
        foreach ($this->routers as $router) {
            $page = $router->getPage($target);
            if ($page != null) {
                return $page;
            }
        }
        
        throw new RouteException("Page with token '$target' not found.");
    }
}
