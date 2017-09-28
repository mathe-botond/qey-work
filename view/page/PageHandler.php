<?php
namespace QeyWork\View\Page;

use QeyWork\Common\ReturnValueException;
use QeyWork\Common\Routers\Arguments;
use QeyWork\Common\Routers\PageRouteCollection;
use QeyWork\Resources\History;

class PageHandler {
    /** @var History */
    private $history;
    /**  @var Arguments  */
    private $token;

    /** @var ILayout */
    protected $layout;
    
    protected $pagePostProcessor;

    public function __construct(
            Arguments $token,
            History $history) {
        
        $this->token = $token;
        $this->history = $history;
    }
    
    public function setPagePostProcessor(IPagePostProcessor $processor) {
        $this->pagePostProcessor = $processor;
    }
    
    public function postProcess(IPage $page) {
        if ($this->token->isFrontPage()) {
            $page->setType(Page::FRONT_PAGE);
        }
            
        if ($this->pagePostProcessor !== null) {
            $page = $this->pagePostProcessor->process($page);
            if (! $page instanceof IPage) {
                throw new ReturnValueException('PageHandler::postProcess', 'IPage', $page);
            }
        }
        return $page;
    }
    
    public function getRequestedPage(PageRouteCollection $pages) {
        $pageClass = $pages->getCurrentPage($this->token);
        
        $this->history->addCurrent();
        
        return $pageClass;
    }
}
