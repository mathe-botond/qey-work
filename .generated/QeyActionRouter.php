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
                return '\\qeywork\\ModelDispacherForExternalCall';
            case LocationsForClients::NAME:
                return '\\qeywork\\LocationsForClients';
            default:
                return null;
        }
    }
}
