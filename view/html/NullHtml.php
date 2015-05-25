<?php
namespace qeywork;

/**
 * @author Dexx
 */
class NullHtml implements IHtmlObject {
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
