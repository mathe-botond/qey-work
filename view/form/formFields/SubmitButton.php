<?php
namespace qeywork;

/**
 * @author Dexx
 */
class SubmitButton implements IRenderable {
    protected $button;
    
    public function __construct($label) {
        $h = new HtmlFactory();
        $this->button = $h->input()->type('submit')->value($label)->cls('submit-button');
    }
    
    public function getInput() {
        return $this->button;
    }
    
    public function render() {
        return $this->button;
    }    
}
