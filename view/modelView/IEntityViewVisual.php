<?php
namespace QeyWork\View\ModelView;

use QeyWork\View\IVisual;

interface IEntityViewVisual extends IVisual {
    /**
     * The skeleton of the view
     * @param string $entries of the view, result of several entry() calls chaimed
     */
    public function base($entries);
    
    /**
     * A view entry
     * @param string $label of the entry
     * @param string $value of the entry
     */
    public function entry($label, $value);
}
