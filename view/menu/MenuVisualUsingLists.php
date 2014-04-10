<?php
namespace qeywork;

class MenuVisualUsingLists implements IMenuVisual {
    /** @var HtmlFactory */

    public function container($content, $id = null, $class = null) {
        $h = new HtmlFactory();
        $menu = $h->ul()->content($content);
        if ($class !== null) {
            $menu->cls($class);
        }
        if ($id !== null) {
            $menu->id($id);
        }
        return $menu;
    }
    
    public function item($label, Url $target, MenuEntityCollection $submenu = null, $name = '', $class = null, $iconImage = null) {
        $h = new HtmlFactory();
        $icon = '';
        if ($iconImage != null) {
             $icon = $h->img()->cls($name . '-icon')->attr('src', $iconImage);
        }
        
        $item = $h->li()->content(
            $h->a()->cls($name . '-link')->href($target)->content(
                $h->span()->cls($name . '-label')->text($label),
                $icon
            )
        );
        
        if ($class != null) {
            $item->cls($class);
        }
        
        if ($submenu != null) {
            $item->append($submenu);
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
