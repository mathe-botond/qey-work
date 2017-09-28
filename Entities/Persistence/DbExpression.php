<?php
namespace QeyWork\Entities\Persistence;

class DbExpression {
    private $expression;
    private $params;
    
    const NOW = 'NOW()';
    
    public function __construct($expression, $params = array()) {
        $this->expression = $expression;
        if (! is_array($params))
            $params = array();
        
        $this->params = $params;
    }
    
    public function getParams() {
        return $this->params;
    }
    
    public function toString() {
        return $this->expression;
    }
}