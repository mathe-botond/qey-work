<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ModelDisplay {
    private $model;
    protected $fields;
    
    public function __construct(Model $type) {
        $this->model = $type;
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

    public function injectModel(Model $model) {
        $paramType = get_class($this->model);
        if (! $model instanceof $paramType) {
            throw new TypeException($model, $paramType);
        }
        
        $injectFields = $model->getFields();
        foreach ($this->model->getFields() as $key => $field) {
            $field->setValue( $injectFields[$key]->value() );
        }
        $this->model->setId($model->getId());
    }
    
    public function getFields() {
        return $this->fields;
    }
}
