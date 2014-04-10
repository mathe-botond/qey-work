<?php
namespace qeywork;

/**
 * @author Dexx
 */
abstract class HtmlEntity implements IHtmlEntity {
    public function __toString() {
        return $this->render() . '';
    } 
}
