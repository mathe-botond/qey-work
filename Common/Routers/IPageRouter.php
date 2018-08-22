<?php
namespace QeyWork\Common\Routers;
use QeyWork\View\Page\Page;

/**
 * Description of PageCollection
 *
 * @author Dexx
 */
interface IPageRouter {
    /**
     * @param Arguments $token
     * @return Page
     */
    public function getPage(Arguments $token);
}
