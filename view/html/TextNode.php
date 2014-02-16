<?php
namespace qeywork;

/**
 * Class used for text contents within html. Has no opening or closing tags.
 * Because HtmlNodes can only contain HtmlEnities, this class provides a way to
 * add text to Html
 *
 * @author Dexx
 */
class TextNode implements IHtmlEntity {
    protected $text;
    
    /**
     * Costructor of this value class.
     * @param string $text
     */
    public function __construct($text = '') {
        $this->text = $text;
    }
    
    public function get() {
        return $this->text;
    }
    
    public function render() {
        return $this;
    }
    
    public function __toString() {
        return $this->get();
    }
}

?>
