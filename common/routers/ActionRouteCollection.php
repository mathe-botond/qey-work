<?php
namespace qeywork;

class ActionRouteCollection {    
    private $defaultRouter;
    private $routers = array();

    public function __construct() {
        $this->defaultRouter = new ActionRouter();
    }
    
    public function addActionClass($token, $className) {
        $this->defaultRouter->addActionClass($token, $className);
    }
    
    public function addRouter(IActionRouter $router) {
        $this->routers[] = $router;
    }
    
    public function getCurrentAction(Arguments $target) {
        $action = $this->defaultRouter->getAction($target);
        if ($action != null) {
            return $action;
        }
        
        foreach ($this->routers as $router) {
            $action = $router->getAction($target);
            if ($action != null) {
                return $action;
            }
        }
        
        throw new RouteException("Action with target '$target' not found.");
    }
}
