<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ReferenceField extends TypedField {
    protected $model;
    protected $modelType;
 
    public function __construct($name, Model $type) {
        parent::__construct($name, TypedField::INT_TYPE);
        
        $this->canBeNull(false);
        $this->modelType = $type;
    }
    
    /**
     * Get referenced Model type as instance of that model
     * Useful for model information without referenced model being loaded
     * @return Model
     */
    public function getModelType() {
        return $this->modelType;
    }
    
    public function setModel(Model $model = null) {
        $this->model = $model;
        if ($model == null) {
            $this->setValue(null);
        } else {
            $this->setValue($this->model->getId());
        }
    }
    
    /**
     * Get referenced model
     * @return Model
     * @throws ModelException when referenced model is not loaded
     */
    public function getModel() {
        if ($this->value() != null && $this->model == null ||
                $this->model != null && $this->model->getId() != $this->value()) {
            throw new ModelException('Referenced model not loaded');
        }
        
        return $this->model;
    }
    
    public function setValue($value) {
        parent::setValue($value);
        
        if ($value == null) {
            $this->model = null;
        }
    }
}
