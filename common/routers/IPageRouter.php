<?php
namespace qeywork;

/**
 * Description of PageCollection
 *
 * @author Dexx
 */
interface IPageRouter {
    /**
     * @return Page
     */
    public function getPage(Arguments $token);
}
