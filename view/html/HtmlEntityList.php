<?php
namespace qeywork;

/**
 * HtmlContainer is an array object for containing Html elements, also provides
 * specific Html search functions
 *
 * @author Dexx
 */
class HtmlEntityList extends SmartArrayObject implements IHtmlEntity {
    /**
     * @param mixed $index
     * @param IHtmlEntity $newval
     * @return IHtmlEntity
     */
    public function offsetSet($index, $newval) {
        if (! $newval instanceof IHtmlEntity) {
            throw new TypeException('Parameter $newval', 'IHtmlEntity');
        }
        return parent::offsetSet($index, $newval);
    }
    
    /**
     * @param mixed $index
     * @return IHtmlEntity
     */
    public function offsetGet($index) {
        return parent::offsetGet($index);
    }
    
    /**
     * @param type $index
     * @param IHtmlEntity $newval
     * @return IHtmlEntity
     */
    public function __set($index, $newval) {
        if (! $newval instanceof IHtmlEntity) {
            throw new TypeException('Parameter $newval', 'IHtmlEntity');
        }
        return parent::__set($index, $newval);
    }
    
    /**
     * @param type $index
     * @return IHtmlEntity
     */
    public function __get($index) {
        return parent::__get($index);
    }
    
    public function __toString() {
        $return = '';
        foreach ($this->getArray() as $items) {
            $return .= $items->__toString();
        }
        return $return;
    }
    
    public function add(IHtmlEntity $value) {
        $this->append($value);
    }

    public function append(IHtmlEntity $value) {
        if ($value instanceof HtmlEntityList) {
            foreach ($value as $key => $item) {
                if ($this->exists($key)) {
                    $this[] = $item;
                } else {
                    $this[$key] = $item;
                }
            }
        } else {
            $this[] = $value;
        }
    }
    
    public function render() {
        return $this;
    }
}

?>
