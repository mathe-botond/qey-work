<?php
namespace qeywork;

/**
 * @author Dexx
 */
class FormRichTextField extends FormTextField {
    protected function addDefaultFilters() {
        //yeah... don't... don't do anything
    }

    public function render() {
        $input = parent::render();
        $input->cls('rich-text-editor');
        return $input;
    }
}

?>
