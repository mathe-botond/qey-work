<?php
namespace QeyWork\Entities\Fields;
use QeyWork\Common\EntityException;
use QeyWork\Entities\Entity;

/**
 * @author Dexx
 */
class ReferenceField extends TypedField {
    /** @var Entity */
    protected $entity;

    /** @var Entity */
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
     * @param bool $unsecure
     * @return Entity
     * @throws EntityException when referenced entity is not loaded
     */
    public function getEntity($unsecure = false) {
        if (! $unsecure && ! $this->isEntityLoaded()) {
            throw new EntityException('Referenced entity not loaded');
        }
        
        return $this->entity;
    }

    public function isEntityLoaded() {
        return $this->value() != null && $this->entity != null && $this->entity->getId() == $this->value();
    }
    
    public function setValue($value) {
        parent::setValue($value);
        
        if ($value == null && $this->entity->getId() != null) {
            $this->entity = null;
        }
    }

    public function loadLinkedId() {
        $this->value = $this->entity->getIdField()->value();
    }
}
