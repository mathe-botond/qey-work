<?php
namespace qeywork;

/**
 * @author Dexx
 */
interface IHtmlEntity extends IRenderable {
    public function __toString();
    public function toString();
}
