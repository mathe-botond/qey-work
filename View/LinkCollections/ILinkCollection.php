<?php
namespace QeyWork\View\LinkCollections;
use QeyWork\Common\Addresses\Url;
use QeyWork\View\IRenderable;

/**
 * @author Dexx
 */
interface ILinkCollection extends IRenderable {
    /**
     * Add file to collection
     * @internal param Url $file File(s) to add to colelction
     */
    public function add();
}
