<?php
namespace qeywork;

/**
 * List of js links used in HTML header
 *
 * @author Dexx
 */
class JsLinkCollection extends LinkCollection
{
    protected $appJs;
    protected $qeyWorkJs;
    
    public function __construct(Locations $loc) {
        $this->appJs = $loc->appJs;
        $this->qeyWorkJs = $loc->qeyWorkJs;
        
        parent::__construct();
    }
    
    protected function createEntry($file) {
        $h = new HtmlFactory();
        return $h->script()->type('text/javascript')->defer()->src($file);
    }
    
    /**
     * @return Url
     */
    public function getAppJs() {
        return $this->appJs;
    }
    
    /**
     * @return Url
     */
    public function getQeyWorkJs() {
        return $this->qeyWorkJs;
    }
}
