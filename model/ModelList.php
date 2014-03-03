<?php
namespace qeywork;

/**
 * HtmlContainer is an array object for containing Html elements, also provides
 * specific Html search functions
 *
 * @author Dexx
 */
class ModelList extends SmartArrayObject implements IModelEntity {
    /** @var Model */
    protected $type;
    
    public function __construct(Model $type, $array = array()) {
        parent::__construct($array);
        $this->type = $type;
    }
    
    public function add(Model $model) {
        $this[] = $model;
    }
    
    public function append(ModelList $value) {
        foreach ($value as $key => $item) {
            if ($this->array->offsetExists($key)) {
                $this[] = $item;
            } else {
                $this[$key] = $item;
            }
        }
    }
    
    public function getModelType() {
        return $this->type;
    }
}

?>