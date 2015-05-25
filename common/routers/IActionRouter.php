<?php
namespace qeywork;

/**
 * Description of PageCollection
 *
 * @author Dexx
 */
interface IActionRouter {
    /**
     * @return IAction
     */
    public function getAction(Arguments $token);
}
