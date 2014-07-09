<?php
namespace qeywork;

class QeyEngine {
    /** @var Resources */
    protected $resources;
    /** @var ILayout */
    protected $layout;
    /** @var User */
    protected $user;
    
    protected $pagePostProcessor;
    
    public function __construct(
            Resources $resources,
            User $user) {
        
        $this->resources = $resources;
        $this->user = $user;
    }
    
    /**
     * @return BasicResources
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
    
    public function setPagePostProcessor(IPagePostProcessor $processor) {
        $this->pagePostProcessor = $processor;
    }
    
    protected function postProcess(IPage $page) {
        if ($this->pagePostProcessor !== null) {
            $page = $this->pagePostProcessor->process($page);
            if (! $page instanceof IPage) {
                throw new ReturnValueException($page, 'IPage');
            }
        }
        return $page;
    }
    
    public function createPage(ILayout $layout, PageFactoryCollection $pages) {
        $target = $this->resources->getParams()->getRequestedTarget();
        $page = $pages->getCurrentPage($target);
        $page = $this->postProcess($page);
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
