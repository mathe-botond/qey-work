<?php
namespace QeyWork\View\Forms\Fields;
use QeyWork\Entities\Fields\Field;
use QeyWork\View\Forms\Filters\XssFilter;

/**
 * A Field is a member of a Entity.
 *
 * @author Dexx
 */
class FormField {
    /** @var Field */
    protected $field;
    
    public $label;
    public $dataSourceControl;
    public $readOnly;
    /** @var array */
    public $validators;
    /** @var array */
    public $filters;
    public $class;
    public $comment;
    public $errors;
    
    protected function addDefaultFilters() {
        $this->addFilter(new XssFilter());
    }
    
    public function __construct(Field $field) {
        $this->field = $field;
        
        $this->validators = new SmartArray();
        $this->filters = new SmartArray();
        
        $this->errors = null;
    }
    
    public function getField() {
        return $this->field;
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
    
    public function addFilter(ValueFilter $filter) {
        $this->filters[$filter->getName()] = $filter;
        return $this;
    }
    
    public function setClass($class) {
        $this->class = $class;
        return $this;
    }
    
    public function setValue($value) {
        foreach ($this->filters as $filter) {
            $value = $filter->execute($value);
        }
        
        $this->field->setValue($value);
        return $this;
    }
    
    public function setDataSource(IFieldDataSourceControl $dataSource) {
        $this->dataSourceControl = $dataSource;
        return $this;
    }
    
    public function value() {
        return $this->field->value();
    }
    
    public function isReadOnly() {
        return $this->readOnly == true;
    }
    
    public function getName() {
        return $this->field->getName();
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
    
    public function isEmpty() {
        $value = $this->value();
        return empty($value);
    }
    
    public function cleanValidation() {
        $this->errors = null;
    }
    
    public function toClientEntity() {
        $clientValidatorData = array();
        foreach ($this->validators as $validator) {
            /* @var $validator Validator */
            $clientValidatorData[$validator->getName()] = $validator->getMessage();
        }
        
        $clientEntity = array(
            'label' => $this->label,
            'readonly' => $this->isReadOnly(),
        );
        
        if ($this->class != null) {
            $clientEntity['class'] = $this->class;
        }
        
        $clientEntity['validators'] = $clientValidatorData;
        
        return $clientEntity;
    }
    
    public function render(HtmlBuilder $h) {

        return $h->input()
                ->type('text')
                ->cls($this->class)
                ->name($this->field->getName())
                ->value($this->field->value());
    }
    
    
    public function __toString() {
        return $this->render($h) . '';
    }
}
