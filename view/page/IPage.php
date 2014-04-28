<?php
namespace qeywork;

interface IPage extends IBlock {
    const NO_SPECIAL_TYPE = 0;
    const FRONT_PAGE = 1;
    
    /**
     * Get the title of this page
     * @return null|string Title 
     */    
    public function getTitle();
    
    public function setType($type);
    
    public function isFrontPage();
}
