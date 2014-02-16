<?php
namespace qeywork;

/**
 * @author Dexx
 */
class HtmlWrapperNode extends HtmlEntity {
    protected $content;
    
    public function __construct($content) {
        $this->content = $content . '';
    }

    public function render() {
        return $this->content;
    }
}

?>
