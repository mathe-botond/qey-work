<?php
namespace qeywork;

class ActionFactoryCollection {
    /** @var Params */
    protected $params;
    
    protected $collection;
    
    public function __construct(Params $params) {
        $this->collection = array();
        $this->params = $params;
    }
    
        
    public function addFactory(IActionFactory $factory) {
        array_unshift($this->collection, $factory);
    }
    
    public function getCurrentAction() {
        $name = $this->params->getRequestedTarget();
        
        foreach ($this->collection as $factory) {
            /* @var $factory \IActionFactory */
            $action = $factory->getAction($name);
            if ($action != null) {
                return $action;
            }
        }
        if ($action == null) {
            throw new ClientDataException('No action found');
        }
    }
}
?>
