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
                return '\\qeywork\\EntityDispacherForExternalCall';
            case LocationsForClients::NAME:
                return '\\qeywork\\LocationsForClients';
            default:
                return null;
        }
    }
}
