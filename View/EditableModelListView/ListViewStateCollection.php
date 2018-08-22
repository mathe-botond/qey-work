<?php
namespace qeywork;

/**
 * ListView state data is stored in this collection.  
 */
class ListViewStateCollection {
    protected $listViewStates = array();
    
    public function __construct()
    {
        $this->listViewStates = array();
    }
    
    /**
     * @param ListViewState $listViewStates
     */
    public function add($listViewState) {
        $this->listViewStates[$listViewState->getName()] = $listViewState;
    }
    
    /**
     * @param string $name
     * @return ListViewState
     * @throws ArgumentException 
     */
    public function get($name) {
        if (!array_key_exists($name, $this->listViewStates)) {
            self::add(new ListViewState($name));
        }
        return $this->listViewStates[$name];
    }
    
    public function remove($name) {
        if (isset($this->listViewStates[$name])) {
            unset($this->listViewStates[$name]);
        }
    }
}
