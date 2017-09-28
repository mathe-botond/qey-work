<?php
namespace QeyWork\View;

use QeyWork\Common\Routers\Arguments;
use QeyWork\Common\Routers\ContentRouteCollection;
use QeyWork\Resources\History;
use QeyWork\View\Block\IBlock;

class ContentHandler {
    /** @var History */
    private $history;
    /**  @var Arguments  */
    private $token;
    
    protected $pagePostProcessor;

    public function __construct(
            Arguments $token,
            History $history) {
        
        $this->token = $token;
        $this->history = $history;
    }
    
    public function getRequestedPage(ContentRouteCollection $pages) {
        $pageClass = $pages->getCurrentContent($this->token);
        
        $this->history->addCurrent();
        
        return $pageClass;
    }
}
