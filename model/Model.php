<?php
namespace qeywork;

/**
 * @author Dexx
 */
class Model extends Friendly {
    const FIELD = 'field';
    
    /** @var IPersistentData */
    private $persistenceData;

    const ID_FIELD_NAME = 'id';
    protected $id;
    protected $idField;
    /** @var SmartArray */
    protected $fields;
    
    protected function addClassPropertiesAsFields() {
        if ($this->fields == null) {
            $this->fields = new SmartArray();
        }

        foreach ($this as $key => $field) {
            if (in_array($key, array('id', 'fields'))) {
                continue;
            }
            
            if ($field == null) {
                
                $converter = new CaseConverter($key, CaseConverter::CASE_CAMEL);
                $this->add(new WrapperField($converter->toUnderscoredCase(), $this, $key));
                
            } else if ($field instanceof Field && $field->getName() != self::ID_FIELD_NAME) {
                
                $this->add($field);
                
            }
        }
    }
    
    public function __construct(IPersistentData $persistenceData) {
        parent::__construct(array('WrapperField'));
        
        $this->idField = new Field(self::ID_FIELD_NAME);
        
        $this->persistenceData = $persistenceData;
        
        $this->addClassPropertiesAsFields();
    }
    
    public function add(Field $field) {
        $this->fields[ $field->getName() ] = $field;
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
    
    public function getPersistenceData() {
        return $this->persistenceData;
    }
}
