<?php
namespace qeywork;

/**
 * @author Dexx
 */
class LocationsForClients implements IAction {
    const NAME = 'qey-locations-for-clients';
    private $loc;
    
    public function __construct(Locations $loc) {
        $this->loc = $loc;
    }
    
    protected function sendContentType() {
        header('Content-Type: application/javascript');
    }
    
    public function execute() {
        $this->sendContentType();
        
        $loc = array();
        foreach ($this->loc as $name => $location) {
            if ($location instanceof Url) {
                $loc[$name] = $location->toString();
            }
        }
        $loc = json_encode($loc, 64);
        echo 'var qey_locations = ' . $loc . ';';
    }
}

?>