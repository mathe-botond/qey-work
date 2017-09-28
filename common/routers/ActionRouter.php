<?php
namespace QeyWork\Common\Routers;

/**
 * @author Dexx
 */
class ActionRouter implements IActionRouter {
    /** @var array */
    private $actions;
    
    public function __construct() {
        $this->actions = array();
    }
    
    public function addActionClass($token, $className) {
        $this->actions[$token] = $className;
    }
    
    public function getAction(Arguments $token) {
        $sToken = $token->toString();
        if (array_key_exists($sToken, $this->actions)) {
            return $this->actions[$sToken];
        } else {
            return null;
        }
    }
}
