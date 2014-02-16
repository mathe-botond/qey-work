<?php
namespace qeywork;

interface IPage extends IBlock {
    /**
     * Get the title of this page
     * @return null|string Title 
     */    
    public function getTitle();
}
?>