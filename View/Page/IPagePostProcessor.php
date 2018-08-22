<?php
namespace QeyWork\View\Page;

/**
 * An interface describing a page processor, that can modify 
 * or even replace a page after render, before output in a global
 * manner.
 * 
 * useful for example to attach decorators to the page (e.g. sidebar)
 * 
 * @author Dexx
 */
interface IPagePostProcessor {
    //process page
    public function process(IPage $page);
}
