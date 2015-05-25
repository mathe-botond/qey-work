<?php
namespace qeywork;

/**
 * @author Dexx
 */
class FormFileField extends FormField {
    public function render(HtmlBuilder $h) {

        return $h->input()->name( $this->getName() )->type('file');
    }
}
