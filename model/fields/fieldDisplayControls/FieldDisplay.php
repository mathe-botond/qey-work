<?php
namespace qeywork;

/**
 * @author Dexx
 */
abstract class FieldDisplay implements IRenderable {
    protected $value;
    protected $visible = true;
    
    public function setValue($value) {
        $this->value = $value;
    }
    
    public function hide() {
        $this->visible = false;
    }
    
    public function show() {
        $this->visible = true;
    }
    
    public function isVisible() {
        return $this->visible;
    }
    
    public abstract function render();
}

?>
