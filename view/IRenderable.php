<?php
namespace QeyWork\View;
use QeyWork\View\Html\HtmlBuilder;

/**
 * Any object that can render itself as a HTML tree
 * @author Dexx
 */
interface IRenderable {
    public function render(HtmlBuilder $h);
}
