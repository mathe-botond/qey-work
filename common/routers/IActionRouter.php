<?php
namespace QeyWork\Common\Routers;
use QeyWork\Common\IAction;

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
