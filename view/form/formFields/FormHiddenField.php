<?php
namespace qeywork;

/**
 * @author Dexx
 */
class FormHiddenField extends FormField {    
    public function render() {
        $h = new HtmlFactory();
        return $h->input()
                ->type('hidden')
                ->name($this->field->getName())
                ->value($this->field->value());
    }
}
