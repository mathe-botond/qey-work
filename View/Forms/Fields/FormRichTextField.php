<?php
namespace QeyWork\View\Forms\Fields;
use QeyWork\View\Html\HtmlBuilder;

/**
 * @author Dexx
 */
class FormRichTextField extends FormTextField {
    protected function addDefaultFilters() {
        //this is overriding a parent function
        //which adds HTML filtering and whatnot by default,
        //but this time don't do anything
    }

    public function render(HtmlBuilder $h) {
        $input = parent::render($h);
        $input->cls('rich-text-editor');
        return $input;
    }
}
