<?php
namespace qeywork;

/**
 * @author Dexx
 */
abstract class HtmlEntity implements IHtmlEntity {
    public function toString() {
        return $this->render() . '';
    }

    public function __toString() {
        return $this->toString();
    }
}
