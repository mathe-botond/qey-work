<?php
namespace qeywork;

/**
 * Default implementation of the IBasicPage interface
 * @author Dexx
 */
abstract class Page implements IPage
{   
    /**
     * Generates title from class name
     * @return string Title 
     */
    public function getTitle() {
        return null;
    }
}
?>