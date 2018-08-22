<?php
/**
 * Author: Mathe E. Botond
 */

namespace QeyWork\Components;

use QeyWork\View\Html\HtmlBuilder;
use QeyWork\View\Html\HtmlWrapperNode;
use QeyWork\View\IRenderable;
use QeyWork\View\Page\Page;
use ReflectionClass;

abstract class Component extends Page implements IRenderable {
    abstract function build();

    public function render(HtmlBuilder $h) {
        $this->build();

        $reflector = new ReflectionClass($this);
        $childFile = $reflector->getFileName();
        $view = preg_replace('/(.*?)\\.php$/', '$1View.php', $childFile);
        ob_start();
        /** @noinspection PhpIncludeInspection */
        include($view);
        $rendered = ob_get_clean();
        return new HtmlWrapperNode($rendered);
    }
}
