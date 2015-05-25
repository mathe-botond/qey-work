<?php
namespace qeywork;

/**
 * A Field is a member of a Model.
 *
 * @author Dexx
 */
class Field implements IModelEntity, IHtmlEntity {
    protected $name;
    protected $value;
    
    public function __construct($name) {
        $this->name = $name;
    }
    
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }
    
    public function value() {
        return $this->value;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function isEmpty() {
        $value = $this->value();
        return empty($value);
    }

    public function __toString() {
        return $this->toString();
    }

    public function render() {
        return $this;
    }

    public function toString() {
        return $this->value() . '';
    }
}
