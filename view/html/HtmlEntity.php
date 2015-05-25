<?php
namespace qeywork;

/**
 * @author Dexx
 */
abstract class HtmlObject implements IHtmlObject {
    public function toString() {
        return $this->render($h) . '';
    }

    public function __toString() {
        return $this->toString();
    }
}
