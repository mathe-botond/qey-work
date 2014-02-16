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
    
    public function __construct(Model $model, $submitLabel) {
        $this->submit = new SubmitButton($submitLabel);
        $this->model = $model;
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
}

?>
