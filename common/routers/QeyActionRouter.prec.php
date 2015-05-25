<?php
namespace qeywork;

/**
 * @author Dexx
 */
class QeyActionRouter implements IActionRouter {    
    public function getAction(Arguments $args) {
        $token = $args->toString();
        switch ($token) {
            case EntityDispacherForExternalCall::NAME:
                return EntityDispacherForExternalCall::class;
            case LocationsForClients::NAME:
                return LocationsForClients::class;
            default:
                return null;
        }
    }
}
