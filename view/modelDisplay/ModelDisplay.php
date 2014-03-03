<?php
namespace qeywork;

/**
 * @author Dexx
 */
abstract class ModelDisplay {
    protected $fields;
    
    protected function addClassPropertiesAsFields() {
        if ($this->fields == null) {
            $this->fields = new SmartArrayObject();
        }
        
        foreach ($this as $key => $field) {
            if ($field instanceof FieldDisplay) {
                $this->fields[$key] = $field;
            }
        }
    }
    
    protected abstract function getModelType();
    
    protected abstract function createFields(Model $model);

    public function injectModel(Model $model) {
        $paramType = get_class($this->getModelType());
        if (! $model instanceof $paramType) {
            throw new TypeException($model, $paramType);
        }
        
        $this->createFields($model);
        $this->addClassPropertiesAsFields();
    }
    
    public function getFields() {
        return $this->fields;
    }
}

?>
