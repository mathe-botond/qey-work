<?php
namespace qeywork;

/**
 * @author Dexx
 */
class RichTextFormField extends FormField {    
    public function render() {
        $h = new HtmlFactory();
        
        return $h->textarea()
                ->cls('rich-text-editor ' . $this->class)
                ->name($this->name)
                ->content($this->value);
    }
}

?>
