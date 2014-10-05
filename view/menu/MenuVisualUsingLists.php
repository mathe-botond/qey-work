<?php
namespace qeywork;

class MenuVisualUsingLists implements IMenuVisual {
    /** @var HtmlFactory */

    public function menu($content, $id = null, $class = null) {
        $h = new HtmlFactory();
        $menu = $h->nav()->content($content);
        if ($class !== null) {
            $menu->cls($class);
        }
        if ($id !== null) {
            $menu->id($id);
        }
        return $menu;
    }
    
    protected function createLabel($label, $name) {
        $h = new HtmlFactory();
        return $h->span()->cls($name . 'label menu-item-label')->text($label);
    }
    
    protected function createIcon($iconImage, $name) {
        $h = new HtmlFactory();
        if ($iconImage != null) {
            return $h->img()->cls($name . 'icon menu-item-icon')->attr('src', $iconImage);
        }
        return '';
    }
    
    protected function createLink($label, Url $target, $name, $iconImage) {
        $h = new HtmlFactory();
        return $h->a()->cls($name . 'link menu-item-link')->href($target)->content(
            $this->createLabel($label, $name),
            $this->createIcon($iconImage, $name)
        );
    }
    
    public function item(
            $label,
            Url $target,
            IRenderable $submenu = null, 
            $name = '',
            $iconImage = null,
            $class = null,
            $style = null) {
        
        if ($name != '') {
            $name .= '-';
        }
        
        $h = new HtmlFactory();
        
        $item = $h->li()->content(
            $this->createLink($label, $target, $name, $iconImage)
        );
        
        if ($class != null) {
            $item->cls($class);
        }
        
        if ($style != null) {
            $item->style($style);
        }
        
        if ($submenu != null) {
            $item->append($submenu->render());
        }
        
        return $item;
    }
    
    public function itemGroup($items, $class = null) {
        $h = new HtmlFactory();
        $group = $h->ul()->content($items);
        if ($class !== null) {
            $group->cls($class);
        }
        
        return $group;
    }
}
