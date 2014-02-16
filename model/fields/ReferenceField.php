<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ReferenceField extends Field {
    protected $type;
 
    public function __construct($name, Model $type) {
        parent::__construct($name);
        
        $this->type = $type;
    }
}

?>
