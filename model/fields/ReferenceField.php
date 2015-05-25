<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ReferenceField extends TypedField {
    protected $entity;
    protected $entityType;
 
    public function __construct($name, Entity $type) {
        parent::__construct($name, TypedField::INT_TYPE);
        
        $this->canBeNull(false);
        $this->entityType = $type;
    }
    
    /**
     * Get referenced Entity type as instance of that entity
     * Useful for entity information without referenced entity being loaded
     * @return Entity
     */
    public function getEntityType() {
        return $this->entityType;
    }
    
    public function setEntity(Entity $entity = null) {
        $this->entity = $entity;
        if ($entity == null) {
            $this->setValue(null);
        } else {
            $this->setValue($this->entity->getId());
        }
    }
    
    /**
     * Get referenced entity
     * @return Entity
     * @throws EntityException when referenced entity is not loaded
     */
    public function getEntity() {
        if ($this->value() != null && $this->entity == null ||
                $this->entity != null && $this->entity->getId() != $this->value()) {
            throw new EntityException('Referenced entity not loaded');
        }
        
        return $this->entity;
    }
    
    public function setValue($value) {
        parent::setValue($value);
        
        if ($value == null) {
            $this->entity = null;
        }
    }
}
