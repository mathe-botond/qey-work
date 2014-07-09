<?php
namespace qeywork;

/**
 * Description of PageCollection
 *
 * @author Dexx
 */
interface IPageFactory {
    /**
     * @return Page
     */
    public function getPage($token);
}
