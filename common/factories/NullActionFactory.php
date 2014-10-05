<?php
namespace qeywork;

/**
 * An action factory which can be used for websites not having any actions
 *
 * @author Dexx
 */
class NullActionFactory implements IActionRouter {
    public function getAction($token) {
        return null;
    }
}
