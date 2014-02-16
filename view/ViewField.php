<?php
namespace qeywork;

throw new Exception('Not implemented');

/**
 * A Field is a member of a Model.
 *
 * @author Dexx
 */
class ViewField {
    protected $name;
    public $label;
    /** @var FieldInput */
    public $inputControl;
    /** @var FieldDisplay */
    public $displayControl;
    public $dataSourceControl;
    public $readOnly;
    /** @var Validator */
    public $validators;
    public $class;
    public $value;
    public $comment;
    public $errors;
    
    public function __construct($name) {
        $this->name = $name;
        $this->displayControl = new TextDisplay();
        $this->inputControl = new TextInput();
        $this->inputControl->setName($name);
        $this->validators = array();
        $this->errors = null;
    }
    
    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }
    
    public function setReadOnly($readOnly) {
        $this->readOnly = $readOnly;
        return $this;
    }
    
    public function addValidator(Validator $validator) {
        $this->validators[$validator->getName()] = $validator;
        return $this;
    }
    
    public function setClass($class) {
        $this->class = $class;
        return $this;
    }
    
    public function setValue($value) {
        foreach ($this->validators as $validator) {
            $validator->validate($value);
        }
        $this->value = $value;
        if ($this->inputControl != null) {
            $this->inputControl->setValue($value);
        }
        if ($this->displayControl != null) {
            $this->displayControl->setValue($value);
        }
        return $this;
    }
    
    public function setInput(FieldInput $control) {
        $this->inputControl = $control;
        $this->inputControl->setName($this->name);
        $this->inputControl->setValue($this->value);
        return $this;
    }
    
    public function setDisplay(IFieldDisplayControl $control) {
        $this->displayControl = $control;
        $this->displayControl->setValue($this->value);
        return $this;
    }
    
    public function setDataSource(IFieldDataSourceControl $dataSource) {
        $this->dataSourceControl = $dataSource;
        return $this;
    }
    
    public function value() {
        return $this->value;
    }
    
    public function isReadOnly() {
        return $this->readOnly == true;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function addError($type, $message) {
        if (!is_array($this->errors)) {
            $this->errors = array();
        }
        
        $this->errors[$type] = $message;
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function isValid() {
        return empty($this->errors);
    }
    
    public function cleanValidation() {
        $this->errors = null;
    }
    
    public function toClientModel() {
        $clientValidatorData = array();
        foreach ($this->validators as $validator) {
            /* @var $validator Validator */
            $clientValidatorData[$validator->getName()] = $validator->getMessage();
        }
        
        $clientModel = array(
            'label' => $this->label,
            'readonly' => $this->isReadOnly(),
        );
        
        if ($this->inputControl != null) {
            $clientModel['input'] = $this->inputControl->getName();
        }
        
        if ($this->class != null) {
            $clientModel['class'] = $this->class;
        }
        
        $clientModel['validators'] = $clientValidatorData;
        
        return $clientModel;
    }
    
    public function __toString() {
        return $this->value() . '';
    }
}

?>
