<?php
namespace QeyWork\Entities;
use QeyWork\Common\Friendly;
use QeyWork\Common\SmartArray;
use QeyWork\Entities\Fields\Field;
use QeyWork\Entities\Fields\ReferenceField;
use QeyWork\Entities\Persistence\IPersistentData;
use QeyWork\Tools\StringHelpers\CaseConverter;
use stdClass;

/**
 * @author Dexx
 */
class Entity extends Friendly {
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
                $this->$key = new Field($converter->toUnderscoredCase());
            }
                
            if ($this->$key instanceof Field && $this->$key->getName() != self::ID_FIELD_NAME) {
                $this->add($this->$key);
            }
        }
    }
    
    public function __construct(IPersistentData $persistenceData) {
        parent::__construct(array('WrapperField'));
        
        $this->idField = new Field(self::ID_FIELD_NAME);
        
        $this->persistenceData = $persistenceData;
    }

    public function add(Field $field) {
        $this->fields[ $field->getName() ] = $field;
    }

    public function getId() {
        return $this->getIdField()->value();
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
                    ' (you forgot to call the constructor or Entity::addClassPropertiesAsFields() )');
        }
        return $this->fields;
    }
    
    public function getPersistenceData() {
        return $this->persistenceData;
    }

    public function toArray()
    {
        $result = [$this->getIdField()->getName() => $this->getIdField()->value()];
        foreach ($this as $key => $field) {
            if ($field instanceof ReferenceField) {
                if ($field->isEntityLoaded()) {
                    $result[$key] = $field->getEntity()->toArray();
                } else {
                    $result[$key] = new stdClass();
                }
            } else if ($field instanceof Field) {
                $result[$key] = $field->value();
            }
        }
        return $result;
    }
}
