<?php

namespace qeywork;

/**
 * @author Dexx
 */
class FormData {

    const DEFAULT_SUBMIT_LABEL = 'Submit';

    /** @var SmartArray */
    protected $hiddenFields = array();

    /** @var FieldSetCollection */
    protected $fieldSet;

    /** @var SubmitButton */
    protected $submit;
    protected $valid;
    protected $entity;

    /** @var array */
    protected $errors;

    public function __construct(Entity $entity, $submitLabel = self::DEFAULT_SUBMIT_LABEL) {
        $this->fieldSet = new FieldSet();
        $this->fieldSet->setSeamless(true);

        $this->submit = new SubmitButton($submitLabel);
        $this->entity = $entity;
    }

    public function addHiddenField(FormHiddenField $field) {
        $this->hiddenFields[] = $field;
    }

    public function add(FormField $field) {
        $this->fieldSet->addField($field);
    }

    public function addChildFieldSet(FieldSet $fieldSet) {
        $this->fieldSet->addChildFieldSet($fieldSet);
    }
    
    public function setFieldSet(FieldSet $fieldSet) {
        $this->fieldSet = $fieldSet;
    }

    public function validate() {
        $this->valid = true;
        foreach ($this->fieldSet->collectAllFields() as $field) {
            if ($field instanceof FormField) {
                $field->cleanValidation();
                $this->validateField($field);
            }
        }
        return $this->valid;
    }
    
    private function validateField(FormField $field) {
        foreach ($field->validators as $validator) {
            $result = $validator->validate($field->value());

            if (!$result) {
                $this->valid = false;
                $field->addError($validator->getName(), $validator->getMessage());
            }
        }
    }

    public function getFields() {
        return $this->fieldSet->collectAllFields();
    }

    public function getHiddenFields() {
        return $this->hiddenFields;
    }
    
    /**
     * @return FieldSet
     */
    function getFieldSet() {
        return $this->fieldSet;
    }

    /**
     * @param string $name
     * @return FormField
     * @throws ArgumentException
     */
    public function getField($name) {
        $field = $this->fieldSet->getField($name);
        if ($field == null) {
            throw new ArgumentException("Form field '$name' does not exist");
        }
        return $field;
    }

    /**
     * @return SubmitButton submit button of the form
     */
    public function getSubmit() {
        return $this->submit;
    }

    public function getEntity() {
        return $this->entity;
    }

    public function toClientEntity() {
        $descriptor = array();
        foreach ($this->getFields() as $field) {
            $descriptor[$field->getName()] = $field->toClientEntity();
        }
        return $descriptor;
    }

    protected function addClassPropertiesAsFields() {
        if ($this->fieldSet == null) {
            $this->fieldSet = new FieldSet();
        }

        foreach ($this as $key => $field) {
            if ($field instanceof FormField) {
                $this->fieldSet[$key] = $field;
            }
        }
    }
}
