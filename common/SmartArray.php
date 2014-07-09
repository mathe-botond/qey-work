<?php
namespace qeywork;

/**
 * class SmartArray
 * Multidimensional ArrayObject with additional functionality
 *
 * @author Dexx
 */
class SmartArray extends \ArrayObject {
    public function __construct( $array = array(), $nested = true, $flags = 2 ) 
    {
        if (!is_array($array)) {
            throw new ArgumentException('Parameter 1 was expected to be an array');
        }
        
        if ($nested) {
            parent::__construct(array());
            // let’s give the objects the right and not the inherited name
            $class = get_class($this);

            foreach($array as $offset => $value) {
                parent::offsetSet($offset, is_array($value)
                        ? new $class($value)
                        : $value);
            }
        } else {
            parent::__construct($array);
        }

        $this->setFlags($flags);
    }
    
    /**
     * Alias for getArrayCopy()
     * @return array
     */
    public function getArray()
    {
        return $this->getArrayCopy();
    }
    
    public function keys() {
        return array_keys( $this->getArrayCopy() );
    }
    
    public function exists($key) {
        return isset($this[$key]);
    }
    
    public function first() {
        $copy = $this->getArrayCopy();
        $firstItem = reset($copy);
        return $firstItem;
    }
}
