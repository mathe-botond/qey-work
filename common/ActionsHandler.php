<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ActionsHandler {

    /** @var Params */
    private $token;

    public function __construct(Arguments $token) {
        $this->token = $token;
    }
    
    public function getRequestedAction(ActionRouteCollection $actions) {
        return $actions->getCurrentAction($this->token);
    }
}
