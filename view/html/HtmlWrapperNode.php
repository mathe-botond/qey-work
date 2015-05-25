<?php
namespace qeywork;

/**
 * @author Dexx
 */
class HtmlWrapperNode implements IHtmlObject {
    protected $content;
    
    public function __construct($content) {
        $this->content = $content . '';
    }

    public function toString() {
        return $this->content;
    }

    public function __toString() {
        return $this->toString();
    }
}
