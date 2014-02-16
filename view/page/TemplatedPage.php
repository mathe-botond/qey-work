<?php
namespace qeywork;

/**
 * Default implementation of the IBasicPage interface
 * @author Dexx
 */
abstract class TemplatedPage extends TemplatedBlock implements IPage
{   
    /**
     * Generates title from class name
     * @return string Title 
     */
    public function getTitle()
    {
        $classname = get_class($this);
        $title = ltrim(preg_replace('/([A-Z])/', ' $1', $classname));
        return $title;
    }
}
?>