<?php
namespace qeywork;

class QeyEngine {
    /** @var History */
    private $history;
    /**  @var Params  */
    private $params;

    /** @var ILayout */
    protected $layout;
    
    protected $pagePostProcessor;
    
    public function __construct(
            Params $params,
            History $history) {
        
        $this->params = $params;
        $this->history = $history;
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
        $target = $this->params->getRequestedTarget();
        $page = $pages->getCurrentPage($target);
        $page = $this->postProcess($page);
        $layout->setContent($page);
        
        $this->history->addCurrent();
        
        return $layout->render();
    }
    
    public function processRequest(ActionFactoryCollection $actions) {
        $action = $actions->getCurrentAction();
        $action->execute();
    }
}
