<?php
namespace qeywork;

/**
 * @author Dexx
 */
class FormData {
    protected $fields;
    /** @var SubmitButton */
    protected $submit;
    protected $valid;
    protected $model;
    /** @var array */
    protected $errors;
    
    protected function addClassPropertiesAsFields() {
        if ($this->fields == null) {
            $this->fields = new SmartArrayObject();
        }
        
        foreach ($this as $key => $field) {
            if ($field instanceof FormField) {
                $this->fields[$key] = $field;
            }
        }
    }
    
    public function __construct(Model $model, $submitLabel) {
        $this->submit = new SubmitButton($submitLabel);
        $this->model = $model;
        $this->addClassPropertiesAsFields();
    }
    
    public function add(FormField $field) {
        if ($this->fields == null) {
            $this->fields = new SmartArrayObject();
        }
        
        if ($this->fields->offsetExists($field->getName())) {
            throw new ArgumentException('Field with the same name already exists');
        }
        $this->fields[$field->getName()] = $field;
    }
    
    public function validate() {
        $this->valid = true;
        foreach ($this->fields as $field) {
            if ($field instanceof FormField) {
                $field->cleanValidation();
                foreach ($field->validators as $validator) {
                    $result = $validator->validate($field->value());
                    
                    if (! $result) {
                        $this->valid = false;
                        $field->addError($validator->getName(), $validator->getMessage());
                    }
                }
            }
        }
        return $this->valid;
    }
    
    public function getFields() {
        return $this->fields;
    }
    
    /**
     * @return SubmitButton submit button of the form
     */
    public function getSubmit() {
        return $this->submit;
    }
    
    public function getModel() {
        return $this->model;
    }
    
    public function toClientModel() {
        $descriptor = array();
        foreach ($this->getFields() as $field) {
            $descriptor[$field->getName()] = $field->toClientModel();
        }
        return $descriptor;
    }
}

?>
