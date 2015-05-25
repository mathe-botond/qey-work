<?php
namespace qeywork;

/**
 * HtmlContainer is an array object for containing Html elements, also provides
 * specific Html search functions
 *
 * @author Dexx
 */
class HtmlObjectList extends SmartArray implements IHtmlObject {
    /**
     * @param mixed $index
     * @param IHtmlObject $newval
     * @return IHtmlObject
     */
    public function offsetSet($index, $newval) {
        if (! $newval instanceof IHtmlObject) {
            throw new TypeException('Parameter $newval', 'IHtmlEntity');
        }
        return parent::offsetSet($index, $newval);
    }
    
    /**
     * @param type $index
     * @param IHtmlObject $newval
     * @return IHtmlObject
     */
    public function __set($index, $newval) {
        if (! $newval instanceof IHtmlObject) {
            throw new TypeException('Parameter $newval', 'IHtmlEntity');
        }
        return parent::__set($index, $newval);
    }
    
    public function toString() {
        $return = '';
        foreach ($this->getArray() as $items) {
            $return .= $items->__toString();
        }
        return $return;
    }
    
    public function __toString() {
        return $this->toString();
    }
    
    public function add(IHtmlObject $value) {
        $this->append($value);
    }

    public function append($value) {
        if ($value instanceof HtmlObjectList) {
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
    
    public function render(HtmlBuilder $h) {
        return $this;
    }
}
