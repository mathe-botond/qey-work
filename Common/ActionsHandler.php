<?php
namespace QeyWork\Common;
use QeyWork\Common\Routers\ActionRouteCollection;
use QeyWork\Common\Routers\Arguments;
use QeyWork\resources\Request;

/**
 * @author Dexx
 */
class ActionsHandler {

    /** @var Request */
    private $token;

    public function __construct(Arguments $token) {
        $this->token = $token;
    }
    
    public function getRequestedAction(ActionRouteCollection $actions) {
        return $actions->getCurrentAction($this->token);
    }
}
