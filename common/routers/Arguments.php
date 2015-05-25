<?php
namespace qeywork;

/**
 * @author Dexx
 */
class Arguments {
    const TARGET = '_target';
    
    protected $token;
    protected $args;
    
    private function getRequestedTarget() {
        $target = $this->getArgument(0);
        return !empty($target) ? $target : PageRouteCollection::INDEX_TOKEN;
    }  

    public function __construct(Params $params) {
        $this->args = \explode('/', trim( $params->get(self::TARGET) , '/'));
        $this->token = $this->getRequestedTarget();
    }
    
    public function isFrontPage() {
        return $this->token == PageRouteCollection::INDEX_TOKEN;
    }
    
    public function getArgument($number) {
        return isset($this->args[$number]) ? $this->args[$number] : null;
    }
    
    public function getArguments() {
        return $this->args;
    }
    
    public function forceOtherToken($token) {
        $this->token = $token;
    }
    
    public function toString() {
        return $this->token;
    }
    
    public function __toString() {
        return $this->token;
    }
}
