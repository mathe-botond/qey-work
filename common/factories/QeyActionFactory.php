<?php
namespace qeywork;

/**
 * @author Dexx
 */
class QeyActionFactory implements IActionFactory {
    protected $resources;
    
    public function __construct(ResourceCollection $resources) {
        $this->resources = $resources;
    }
    
    public function getAction($name) {
        switch ($name) {
            case ModelDispacherForExternalCall::NAME: 
                return new ModelDispacherForExternalCall(
                    $this->resources->getParams(), 
                    $this->resources->getSession());
                break;
            default:
                return null;
        }
    }
}

?>
