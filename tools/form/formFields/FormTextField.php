<?php
namespace qeywork;

class FormTextField extends FormField {
    public function render() {
        $h = new HtmlFactory();
        return $h->textarea()
            ->cls($this->class)
            ->name($this->field->getName())
            ->value($this->field->value());
    }
}
?>
