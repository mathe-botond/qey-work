<?php
namespace qeywork;

/**
 * @author Dexx
 */
interface IWebsite {
    public function createPage($defaultPage);
    public function processRequest();
}
