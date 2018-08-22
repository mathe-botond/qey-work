<?php
namespace QeyWork\Common\Routers;
use QeyWork\Resources\Request;

/**
 * @author Dexx
 */
class Arguments {
    const TARGET = '_target';
    
    protected $token = "";
    protected $args;

    protected $frontPage;

    private function getRequestedTarget() {
        $target = $this->getArgument(0);
        return !empty($target) ? $target : '';
    }  

    public function __construct(IndexToken $index, Request $params) {
        $this->args = \explode('/', trim($params->get(self::TARGET), '/'));
        $this->token = $this->getRequestedTarget();

        if (trim($this->token) == '') {
            $this->forceOtherToken($index->get());
        }
        $this->frontPage = $index;
    }

    public function isFrontPage() {
        return $this->frontPage->get() == $this->token;
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
