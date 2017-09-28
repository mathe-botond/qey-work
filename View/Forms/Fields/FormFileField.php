<?php
namespace QeyWork\View\Forms\Fields;
use QeyWork\View\Html\HtmlBuilder;

/**
 * @author Dexx
 */
class FormFileField extends FormField {
    public function render(HtmlBuilder $h) {

        return $h->input()->name( $this->getName() )->type('file');
    }
}
