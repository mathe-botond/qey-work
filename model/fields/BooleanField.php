<?php
namespace qeywork;

/**
 * @author Dexx
 */
class BooleanField extends Field {
    public function __construct($name) {
        parent::__construct($name);
        
        $this->inputControl = new CheckBoxInput();
    }
}

?>
