<?php
namespace qeywork;

interface IMenuVisual extends IVisual {
    public function container($content, $id = null, $class = null);
    public function item($label, Url $target, MenuEntityCollection $submenu = null, $name = '', $iconImage = null, $class = '', $style = '');
    public function itemGroup($items, $class = null);
}
