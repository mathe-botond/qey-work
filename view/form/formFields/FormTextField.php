<?php
namespace qeywork;

class FormTextField extends FormField {
    public function render(HtmlBuilder $h) {

        
        return $h->textarea()
                ->cls($this->class)
                ->name($this->getName())
                ->htmlContent($this->value());
    }
}
