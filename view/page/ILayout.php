<?php
namespace qeywork;

/**
 * @author Dexx
 */
interface ILayout extends IBlock {
    public function setContent(IPage $content);
    public function getMeta();
}
