<?php
namespace qeywork;

/**
 * Proxies the content dispatcherc class and displays error pages on errors 
 */
class PageDispatcherWithErrorHandling extends PageDispatcher {
    /**
     * @var PageDispatcher $content the content this class proxies 
     */
    
    public function dispatch() {
        try {   
            return parent::dispatch();
        } catch (ClientDataException $e) {
            return new ErrorDocument('404', 'The requested page could not be found');
        } catch (AuthorizationException $e) {
            if ($e->getCode() == 1) {
                Redirect::gotoLogin();
                exit(); //no output
            } else {
                return new ErrorDocument('403', 'The requested page is not allowed');
            }
        }
    }
}
