<?php
namespace qeywork;

/**
 * A Field is a member of a Model.
 *
 * @author Dexx
 */
class WrapperField extends Field {
    private $valueHolder;
    private $key;
    
    public function __construct($name, $valueHolder, $key) {
        parent::__construct($name);
        
        $this->valueHolder = $valueHolder;
        $this->key = $key;
    }
    
    public function setValue($value) {
        $this->valueHolder->{$this->key} = $value;
        return $this;
    }
    
    public function value() {
        return $this->valueHolder->{$this->key};
    }
}
