<?php
namespace qeywork;

class TypedField extends Field {
    const INT_TYPE = 'int';
    const VARCHAR_TYPE = 'varchar';
    const TEXT_TYPE = 'text';
    
    protected $type;
    protected $size;
    protected $canBeNull;
    
    public function __construct($name, $type) {
        parent::__construct($name);
        
        $this->size = 0;
        $this->canBeNull = true;
        $this->type = $type;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function setSize($size) {
        $this->size = $size;
        return $this;
    }
    
    public function getSize() {
        return $this->size;
    }
    
    public function canBeNull($bool) {
        $this->canBeNull = $bool;
    }
    
    public function getTypeString() {
        $type = $this->name . ' ' .  $this->type;
        
        if ($this->size != 0) {
            $type .= '(' . $this->size . ')';
        }
        
        if ($this->canBeNull) {
            $type .= ' ' . 'null';
        } else {
            $type .= ' ' . 'not null';
        }
        return $type;
    }
}
