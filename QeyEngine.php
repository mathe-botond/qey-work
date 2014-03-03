<?php
namespace qeywork;

class QeyEngine {
    /** @var ResourceCollection */
    public $resources;
    /** @var ILayout */
    public $layout;
    /** @var User */
    public $user;
    
    public function __construct(
            ResourceCollection $resources,
            User $user) {
        
        $this->resources = $resources;
        $this->user = $user;
    }
    
    public function createPage(ILayout $layout, PageFactoryCollection $pages) {
        $target = $this->resources->getParams()->getRequestedTarget();
        $page = $pages->getCurrentPage($target);
        $layout->setContent($page);
        
        $history = $this->resources->getHistory();
        $history->addCurrent();
        
        return $layout->render();
    }
    
    public function processRequest(ActionFactoryCollection $actions) {
        $action = $actions->getCurrentAction();
        $action->execute();
    }
}
?>
