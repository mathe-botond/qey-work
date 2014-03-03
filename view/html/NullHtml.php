<?php
namespace qeywork;

/**
 * @author Dexx
 */
class NullHtml implements IHtmlEntity {
    const EMPTY_RESULT = '';
    
    public function __toString() {
        return self::EMPTY_RESULT;
    }

    public function render() {
        return self::EMPTY_RESULT;
    }    
}

?>
