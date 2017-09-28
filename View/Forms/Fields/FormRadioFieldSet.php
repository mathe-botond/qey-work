<?php
namespace QeyWork\View\Forms\Fields;
use QeyWork\Entities\Fields\Field;
use QeyWork\View\Html\HtmlBuilder;
use QeyWork\View\Html\HtmlNode;

/**
 * @author Dexx <dev@qwerty.ms>
 */
class FormRadioFieldSet extends FormField {
    protected $options;
    
    public function __construct(Field $field) {
        parent::__construct($field);
        $this->options = array();
    }

    public function setLabel($label) {
        $this->label = $label;
    }
    
    public function setOptionList(array $options) {
        $this->options = $options;
    }
    
    public function addOption($value, $label = null) {
        if ($label == null) {
            $label = $value;
        }
        $this->options[$value] = $label;
    }

    public function render(HtmlBuilder $h) {
        if ($this->options == null) {
            throw new \BadMethodCallException('Option list is not set');
        }

        /* @var $input HtmlNode */
        $inputList = $h->ul()->cls('radio-set')->cls($this->class);
        
        foreach ($this->options as $key => $option) {
            $name = $this->getName();
            $id = 'radio-' . $name . '-' . $key;
            $input = $h->input();
            if ($this->field->value() == $key) {
                $input->selected();
            }
            
            $inputList->append($h->li()->cls('radio-set-item')->content(
                $input->id($id)->type('radio')->name($name)->value($key),
                $h->label()->for($id)->text($option)
            ));
        }
        
        return $inputList;
    }
}
