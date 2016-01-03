<?php
namespace qeywork;

class PageRouteCollection {
    private $defaultRouter;
    private $routers = array();

    public function __construct($indexToken) {
        $this->defaultRouter = new PageRouter();
        $this->indexToken = $indexToken;
    }
    
    public function addPageClass($token, $className) {
        $this->defaultRouter->addPageClass($token, $className);
    }
    
    public function addRouter(IPageRouter $router) {
        $this->routers[] = $router;
    }

    /**
     * @param Arguments $target
     * @return string
     * @throws RouteException
     */
    public function getCurrentPage(Arguments $target) {
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
