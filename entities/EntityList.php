<?php
namespace qeywork;

/**
 * HtmlContainer is an array object for containing Html elements, also provides
 * specific Html search functions
 *
 * @author Dexx
 */
class EntityList extends SmartArray implements IEntityType {
    /** @var Entity */
    protected $type;
    
    public function __construct(Entity $type, $array = array()) {
        parent::__construct($array);
        $this->type = $type;
    }
    
    public function add(Entity $entity) {
        $this[] = $entity;
    }
    
    public function append($value) {
        if (! $value instanceof EntityList) {
            throw new TypeException($value, 'EntityList');
        }
        foreach ($value as $key => $item) {
            if ($this->array->offsetExists($key)) {
                $this[] = $item;
            } else {
                $this[$key] = $item;
            }
        }
    }
    
    public function getEntityType() {
        return $this->type;
    }
}
