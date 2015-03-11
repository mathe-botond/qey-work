<?php
namespace qeywork;

/**
 * @author Dexx
 */
class FormData {
    const DEFAULT_SUBMIT_LABEL = 'Submit';
    
    /** @var SmartArray */
    protected $hiddenFields = array();
    
    /** @var SmartArray */
    protected $fields;
    /** @var SubmitButton */
    protected $submit;
    protected $valid;
    protected $model;
    /** @var array */
    protected $errors;
    
    protected function addClassPropertiesAsFields() {
        if ($this->fields == null) {
            $this->fields = new SmartArray();
        }
        
        foreach ($this as $key => $field) {
            if ($field instanceof FormField) {
                $this->fields[$key] = $field;
            }
        }
    }
    
    public function __construct(Model $model, $submitLabel = self::DEFAULT_SUBMIT_LABEL) {
        $this->submit = new SubmitButton($submitLabel);
        $this->model = $model;
        $this->addClassPropertiesAsFields();
    }
    
    public function addHiddenField(FormHiddenField $field) {
        $this->hiddenFields[] = $field;
    }
    
    public function add(FormField $field) {
        if ($this->fields == null) {
            $this->fields = new SmartArray();
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
    
    public function getHiddenFields() {
        return $this->hiddenFields;
    }
    
    /**
     * @param string $name
     * @return FormField
     * @throws ArgumentException
     */
    public function getField($name) {
        if (! $this->fields->exists($name)) {
            throw new ArgumentException("Form field '$name' does not exist");
        }
        return $this->fields[$name];
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
