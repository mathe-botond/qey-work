<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ReferenceField extends Field {
    protected $model;
 
    public function __construct($name, Model $type) {
        parent::__construct($name);
        
        $this->model = $type;
    }
    
    public function getModelType() {
        return $this->model;
    }
    
    public function setModel(Model $model = null) {
        $this->model = $model;
        if ($model == null) {
            $this->setValue(null);
        } else {
            $this->setValue($this->model->getId());
        }
    }
    
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

?>
