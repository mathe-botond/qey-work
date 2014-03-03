<?php
namespace qeywork;

/**
 * A class describing a form. Good for storing information about the form on the server side 
 *
 * @author Dexx
 */
class PostRedirectGetUrls {
    protected $action;
    protected $pageAddress;
    protected $redirect;
    
    public function __construct(
            Url $action,
            Url $pageAddress,
            Url $redirect = null) 
    {
        $this->action = $action;
        $this->pageAddress = $pageAddress;
        if ($redirect != null) {
            $this->redirect = $redirect;
        } else {
            $this->redirect = $this->pageAddress;
        }
        
        //$this->id = getFormDataCollection()->add($this);
    }
    
    public function getAction() {
        return $this->action;
    }
    
    public function getPageAddress() {
        return $this->pageAddress;
    }
    
    public function getRedirect() {
        return $this->redirect;
    }
}

?>
