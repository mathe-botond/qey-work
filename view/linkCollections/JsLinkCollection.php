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
    
    protected function createEntry($file) {
        $h = new HtmlFactory();
        return $h->script()->type('text/javascript')->src($file);
    }
    
    public function setAppJs(Url $appJs) {
        $this->appJs = $appJs;
    }
    
    public function setQeyWorkJs(Url $qeyWorkJs) {
        $this->qeyWorkJs = $qeyWorkJs;
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

?>