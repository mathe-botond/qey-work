<?php
namespace qeywork;

/**
 * @author Dexx
 */
class Model implements IModelEntity {
    const ID_FIELD_NAME = 'id';
    protected $id;
    protected $idField;
    /** @var SmartArray */
    protected $fields;
    
    protected function addClassPropertiesAsFields() {
        if ($this->fields == null) {
            $this->fields = new SmartArray();
        }
        
        foreach ($this as $field) {
            if ($field instanceof Field && $field->getName() != self::ID_FIELD_NAME) {
                $this->add($field);
            }
        }
    }
    
    public function __construct() {
        $this->addClassPropertiesAsFields();
        $this->idField = new Field(self::ID_FIELD_NAME);
    }
    
    public function add(Field $field) {
        $this->fields[ $field->getName() ] = $field;
    }
    
    /** @var IPersistentData $persistanceData Data concerning persistance */
    public $persistanceData; 
    
    public function setPersistanceData(IPersistentData $persistanceData) {
        $this->persistanceData = $persistanceData;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getIdField() {
        return $this->idField;
    }
    
    public function setId($id) {
        $this->idField->setValue($id);
        return $this->id = $id;
    }
    
    /**
     * @param string $name
     * @return Field
     */
    public function getField($name) {
        return $this->fields[$name];
    }
    
    public function getFields() {
        if ($this->fields == null) {
            throw new \BadMethodCallException('Field array is undefined on ' . get_class($this) .
                    ' (you forgot to call the constructor or Model::addClassPropertiesAsFields() )');
        }
        return $this->fields;
    }
}
