<?php
namespace qeywork;

/**
 * @author Dexx
 */
class FileInput extends FieldInput {
    const NAME = 'image';

    public function getName() {
        return self::NAME;
    }
    
    public function render(HtmlBuilder $h) {

        return $h->input->type('file')->name($this->name);
    }
}
