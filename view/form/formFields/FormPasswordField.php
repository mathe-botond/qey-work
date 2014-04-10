<?php
namespace qeywork;

/**
 * @author Dexx
 */
class FormPasswordField extends FormField {
    public function render() {
        $input = parent::render();
        $input->type('password');
        return $input;
    }
}
