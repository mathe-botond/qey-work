<?php
namespace qeywork;

/**
 * @author Dexx
 */
class QeyActionRouter implements IActionRouter {    
    public function getAction(Arguments $args) {
        $token = $args->toString();
        switch ($token) {
            case ModelDispacherForExternalCall::NAME: 
                return ModelDispacherForExternalCall::class;
            case LocationsForClients::NAME:
                return LocationsForClients::class;
            default:
                return null;
        }
    }
}
