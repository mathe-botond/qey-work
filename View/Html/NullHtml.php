<?php
namespace QeyWork\View\Html;
use QeyWork\View\IRenderable;

/**
 * @author Dexx
 */
class NullHtml implements IHtmlObject, IRenderable {
    const EMPTY_RESULT = '';
    
    public function __toString() {
        return self::EMPTY_RESULT;
    }

    public function toString() {
        return self::EMPTY_RESULT;
    }

    public function render(HtmlBuilder $h) {
        return $this;
    }
}
