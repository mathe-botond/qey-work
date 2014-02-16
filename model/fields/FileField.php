<?php
namespace qeywork;

/**
 * @author Dexx
 */
class FileField extends Field {
    public $path;
    
    public function __construct($name) {
        $this->name = $name;
        $this->inputControl = new FileInput();
        $this->inputControl->setName($name);
        $this->validators = array();
        $this->errors = null;
    }
}

?>
