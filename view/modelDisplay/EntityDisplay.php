<?php
namespace qeywork;

/**
 * @author Dexx
 */
class EntityDisplay {
    private $entity;
    protected $fields;
    
    public function __construct(Entity $type) {
        $this->entity = $type;
        $this->addClassPropertiesAsFields();
    }
    
    protected function addClassPropertiesAsFields() {
        if ($this->fields == null) {
            $this->fields = new SmartArray();
        }
        
        foreach ($this as $field) {
            if ($field instanceof FieldDisplay) {
                $this->add($field);
            }
        }
    }
    
    public function add(FieldDisplay $field) {
        if ($this->fields == null) {
            $this->fields = new SmartArray();
        }
        
        if ($this->fields->offsetExists($field->getName())) {
            throw new ArgumentException('Field with the same name already exists');
        }
        $this->fields[$field->getName()] = $field;
    }

    public function injectEntity(Entity $entity) {
        $paramType = get_class($this->entity);
        if (! $entity instanceof $paramType) {
            throw new TypeException($entity, $paramType);
        }
        
        $injectFields = $entity->getFields();
        foreach ($this->entity->getFields() as $key => $field) {
            $field->setValue( $injectFields[$key]->value() );
        }
        $this->entity->setId($entity->getId());
    }
    
    public function getFields() {
        return $this->fields;
    }
}
