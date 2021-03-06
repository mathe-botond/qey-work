<?php
namespace qeywork;

/**
 * @author Dexx
 */
class CheckBoxInput extends TextInput {
    protected $label;
    
    public function render(HtmlBuilder $h) {
        $labeledInput = new HtmlObjectList();
        
        /* @var $input IHtmlObject */
        $input = $h->input()
            ->type('checkbox')
            ->cls($this->class)
            ->name($this->name);
        
        if ($this->value) {
            $input->checked('checked');
        }
        
        $labeledInput[] = $input;
        $labeledInput[] = new TextNode($this->label);
        
        return $labeledInput;
    }
}
