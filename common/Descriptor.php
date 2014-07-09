<?php
namespace qeywork;

/**
 * Descriptor is a JSON representation of an object: e.g. a menu or a model.
 * This class provides basic descriptor functionality, like extending, and acessing
 *
 * @author Dexx
 */
class Descriptor extends SmartArray {    
    public function __construct($descriptor = array())
    {
        $nested = true;
        if ($descriptor instanceof Descriptor) {
            $descriptor = $descriptor->getArray();
            $nested = false;
        } else if (is_string($descriptor)) { //JSON string
            $descriptor = json_decode($descriptor, true);
            if ($descriptor == null)
            {
                throw new ModelException('Model descriptor malformed, json_decode error: ' . getJsonLastErrorString());
                return ;
            }
        } 
        
        parent::__construct($descriptor, $nested);
    }

    public function &getRaw() {
        $result = (array)$this; 
        return $result;
    }
    
    private function _arrayMergeRecursive($array1, $array2) {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if ($value instanceof Descriptor
                    && isset($merged[$key]) && $merged[$key] instanceof Descriptor) {
                $merged[$key] = $this->_arrayMergeRecursive($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }
        return $merged;
    }
    
    public function extendWith($child) {
        $selfDescriptor = new Descriptor($this->getArray());
        return new Descriptor(
                $this->_arrayMergeRecursive($selfDescriptor, $child)
            );
    }
    
    private function _arrayUnsetLeafsRecursive(&$array, &$leafs) {
        $target = new Descriptor($this->getArray());
        foreach ($leafs as $key => &$value) {
            if ($value instanceof Descriptor
                    && isset($target[$key]) && $target[$key] instanceof Descriptor) {
                $target[$key] = $this->_arrayUnsetLeafsRecursive($target[$key], $value);
            } else if (!is_array($value)) {
                unset($target[$key]);
            }
        }
        return $target;
    }
    
    /**
     * @param Descriptor $tree
     * @return Descriptor 
     */
    public function unsetLeafs($tree) {
        return new Descriptor(
                $this->_arrayUnsetLeafsRecursive($this->descriptor, $tree)
            );
    }
    
    function __clone() {
        foreach ($this as $key => $val) {
            if (is_object($val) || (is_array($val))) {
                $this->{$key} = unserialize(serialize($val));
            }
        }
    }
}
