<?php
namespace qeywork;

/**
 * @author Dexx
 */
class FieldSet {
    /** @var SmartArray */
    private $fields;
    
    /** @var SmartArray */
    private $childFieldSets;
    
    /** @var FormFieldSet */
    private $defaultFieldSet;
    
    private $seamless = false;
    
    private $class = "";
    
    private $title;

    public function __construct() {
        $this->fields = new SmartArray();
        $this->childFieldSets = new SmartArray();
    }

    public function collectAllFields() {
        $fields = new SmartArray();
        foreach ($this->fields as $field) {
            $fields->append($field);
        }
        foreach ($this->childFieldSets as $fieldSet) {
            $fields->appendArray($fieldSet->collectAllFields());
        }
        return $fields;
    }
    
    public function getDefaultFieldSet() {
        return $this->defaultFieldSet;
    }

    public function getField($name) {
        $field = null;
        
        if ($this->fields->exists($name)) {
            return $this->fields[$name];
        } else {
            foreach ($this->childFieldSets as $fieldSet) {
                $field = $fieldSet->getField($name);
                if ($field != null) {
                    return $field;
                }
            }
        }
    }

    public function addChildFieldSet(FieldSet $fieldSet) {
        $this->childFieldSets->append($fieldSet);
    }
    
    public function addField(FormField $field) {
        $this->fields->append($field);
    }
    
    function getFields() {
        return $this->fields;
    }

    function getChildFieldSets() {
        return $this->childFieldSets;
    }
    
    function setSeamless($seamless) {
        $this->seamless = $seamless;
    }

    function setClass($class) {
        $this->class = $class;
    }
    
    function isSeamless() {
        return $this->seamless;
    }

    function getClass() {
        return $this->class;
    }
    
    function getTitle() {
        return $this->title;
    }

    function setTitle($title) {
        $this->title = $title;
    }
}
