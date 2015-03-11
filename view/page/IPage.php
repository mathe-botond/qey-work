<?php
namespace qeywork;

interface IPage extends IBlock {
    const NO_SPECIAL_TYPE = 0;
    const FRONT_PAGE = 1;
    const LANDING_PAGE = 2048;

    /**
     * Get the title of this page
     * @return null|string Title 
     */    
    public function getTitle();
    
    public function setType($type);

    /**
     * Get the title of this page
     * @return null|string type
     */
    public function isType($type);

    public function isFrontPage();
}
