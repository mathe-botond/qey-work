<?php
namespace qeywork;

class QeyEngine {
    /** @var ResourceCollection */
    protected $resources;
    /** @var ILayout */
    protected $layout;
    /** @var User */
    protected $user;
    
    public function __construct(
            ResourceCollection $resources,
            User $user) {
        
        $this->resources = $resources;
        $this->user = $user;
    }
    
    /**
     * @return BasicResourceCollection
     */
    public function getResources() {
        return $this->resources;
    }
    
    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
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
