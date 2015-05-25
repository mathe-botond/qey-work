<?php
namespace qeywork;

/**
 * @author Dexx
 */
class FormPasswordField extends FormField {
    public function render(HtmlBuilder $h) {
        $input = parent::render($h);
        $input->type('password');
        return $input;
    }
}
