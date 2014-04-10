<?php
namespace qeywork;

/**
 * @author Dexx
 */
class SubmitButton implements IRenderable {
    protected $label;
    
    public function __construct($label) {
        $this->label = $label;
    }
    
    public function render() {
        $h = new HtmlFactory();
        return $h->input()->type('submit')->value($this->label);
    }    
}
