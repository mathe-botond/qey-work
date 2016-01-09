<?php
namespace qeywork;

/**
 * @author Dexx <dev@qwerty.ms>
 */
class FormDropDownField extends FormField {
    protected $options;
    
    public function __construct(Field $field, ValueListProvider $provider = null) {
        parent::__construct($field);
        if ($provider != null) {
            $this->options = $provider->getValueList();
        }
    }

    public function setLabel($label) {
        $this->label = $label;
    }
    
    public function setOptionList(array $options) {
        $this->options = $options;
    }

    public function render(HtmlBuilder $h) {
        if ($this->options == null) {
            throw new \BadMethodCallException('Option list is not set');
        }
        

        /* @var $input HtmlNode */
        $input = $h->select()->name($this->getName())->cls($this->class);
        foreach ($this->options as $id => $option) {
            $item = $h->option()->value($id)->text($option);
            if ($this->field->value() == $id) {
                $item->selected();
            }
            $input->append($item);
        }
        return $input;
    }
}
