<?php
namespace qeywork;

/**
 * @author Dexx <dev@qwerty.ms>
 */
class DropDownField extends FormField {
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
    
    public function setOptionList(SmartArray $options) {
        $this->options = $options;
    }

    public function render() {
        if ($this->options == null) {
            throw new \BadMethodCallException('Option list is not set');
        }
        
        $h = new HtmlFactory();
        /* @var $input HtmlNode */
        $input = $h->select()->name($this->getName());
        foreach ($this->options as $id => $option) {
            $input->append(
                $h->option()->value($id)->content($option)
            );
        }
        return $input;
    }
}
