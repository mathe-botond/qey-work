<?php
namespace qeywork;

/**
 * @author Dexx
 */
class FormFileField extends FormField {
    public function render() {
        $h = new HtmlFactory();
        return $h->input()->name( $this->getName() )->type('file');
    }
}
