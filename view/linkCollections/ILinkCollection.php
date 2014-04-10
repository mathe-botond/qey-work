<?php
namespace qeywork;

/**
 * @author Dexx
 */
interface ILinkCollection extends IRenderable {
    /**
     * Add file to collection
     * @param Url $file,... File(s) to add to colelction
     */
    public function add();
}
