<?php
namespace qeywork;

/**
 * @author Dexx
 */

/**
 * @property Url $basepath
 * @property string $name
 * 
 * @property Url $listingPage
 * @property Url $additionPage
 * @property Url $editPage
 * 
 * @property Url $additionOperation
 * @property Url $editOperation
 * @property Url $removalOperation
 */
class MMSPathCollection {
    private $basepath;
    private $name;
    
    private $listingPage;
    private $additionPage;
    private $editPage;
    
    private $additionOperation;
    private $editOperation;
    private $removalOperation;
    
    /**
     * Constructor of this class
     * @param Url $basepath
     * @param string $name 
     */
    public function __construct($basepath, $name = null) {
        $this->basepath = $basepath;
        $this->name = $name;
        
        $this->listingPage = $basepath->dir($this->name)->dir('list');
        $this->additionPage = $basepath->dir($this->name)->dir('add');
        $this->editPage = $basepath->dir($this->name)->dir('edit');
        
        $operation = $basepath->addDirs(array('q', $name));
        $this->additionOperation = $operation->dir('add');
        $this->editOperation =  $operation->dir('edit');
        $this->removalOperation = $operation->dir('remove');
    }
        
    public function __get($property) {
        if (isset($this->$property) && $this->$property != null) {
            return $this->$property;
        } else {
            throw new BadMethodCallException('Undefined or uninitialised property: '
                    . $property . ' of class ' . get_class($this));
        }
    }
    
    /*
    public function __set($property, $value) {
        if (isset($this->$property)) {
            $this->$property = $value;
        } else {
            throw BadMethodCallException('Undefined property: ' . $property
                    . ' of class ' . get_class($this));
        }
    }
    */
}
