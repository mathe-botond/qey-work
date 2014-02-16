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
    
    /**
     * @param type $index
     * @param Model $newval
     * @return Model
     */
    public function __set($index, Model $newval) {
        return $this->array[$index] = $newval;
    }
    
    /**
     * @param type $index
     * @return Model
     */
    public function __get($index) {
        return $this->array[$index];
    }
    
    public function add(Model $model) {
        $this->array[] = $model;
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
    
    public function getType() {
        return $this->type;
    }
}

?>