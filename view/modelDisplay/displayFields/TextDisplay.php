<?php
namespace qeywork;

/**
 * @author Dexx
 */
class TextDisplay extends FieldDisplay {
    public function render(HtmlBuilder $h) {
        return $this->field->value();
    }
}
