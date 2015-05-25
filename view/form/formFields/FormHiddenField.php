<?php
namespace qeywork;

/**
 * @author Dexx
 */
class FormHiddenField extends FormField {    
    public function render(HtmlBuilder $h) {

        return $h->input()
                ->type('hidden')
                ->name($this->field->getName())
                ->value($this->field->value());
    }
}
