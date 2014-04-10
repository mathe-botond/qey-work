<?php
namespace qeywork;

/**
 * @author Dexx
 */
class Model implements IModelEntity {
    protected $id;
    /** @var SmartArrayObject */
    protected $fields;
    
    protected function addClassPropertiesAsFields() {
        if ($this->fields == null) {
            $this->fields = new SmartArrayObject();
        }
        
        foreach ($this as $key => $field) {
            if ($field instanceof Field) {
                $this->fields[$key] = $field;
            }
        }
    }
    
    public function __construct() {
        $this->addClassPropertiesAsFields();
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
    
    public function setId($id) {
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
