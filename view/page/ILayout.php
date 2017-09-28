<?php
namespace QeyWork\View\Page;
use QeyWork\View\Block\IBlock;

/**
 * @author Dexx
 */
interface ILayout extends IBlock {
    public function setContent(IPage $content);
    public function getMeta();
}
