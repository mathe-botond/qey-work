<?php
namespace qeywork;

/**
 * A class describing a ListViewState. Good for storing information about the ListViewState 
 *
 * @author hupu
 */
class ListViewState {

    protected $name;

    public $sort;
    public $filter;
    public $from;
    public $to;

    /**
     * Creates an instance of the ListViewState class.
     */
    public function __construct($name, $sort = null, $filter = null, $from = null, $to =  null) {
        $this->name = $name; 

        $this->sort = $sort;
        $this->filter = $filter;
        $this->from = $from;
        $this->to = $to;
    }
    
    /**
     * Gets the name of this ListViewState.
     */
    public function getName() {
        return $this->name;
    }
}

?>
