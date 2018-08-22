<?php
namespace qeywork;

interface IMenuVisual extends IVisual {
    public function menu($content, $id = null, $class = null);
    public function item($label, Url $target, IRenderable $submenu = null, $name = '', $iconImage = null, $class = '', $style = '');
    public function itemGroup($items, $class = null);
}
