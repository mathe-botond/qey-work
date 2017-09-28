<?php
namespace QeyWork\View\ModelDisplay\Fields;
use QeyWork\View\Html\HtmlBuilder;

/**
 * @author Dexx
 */
class TextDisplay extends FieldDisplay {
    public function render(HtmlBuilder $h) {
        return $this->field->value();
    }
}
