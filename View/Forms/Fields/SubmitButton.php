<?php
namespace QeyWork\View\Forms\Fields;
use QeyWork\View\Html\HtmlBuilder;
use QeyWork\View\IRenderable;

/**
 * @author Dexx
 */
class SubmitButton implements IRenderable {
    /** @var string */
    private $label;

    public function __construct($label) {
        $this->label = $label;
    }
    
    public function render(HtmlBuilder $h) {
        return $h->input()->type('submit')->value($this->label)->cls('submit-button');
    }    
}
