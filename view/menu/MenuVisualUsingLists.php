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
        $icon = '';
        if ($iconImage != null) {
            $icon = $h->img()->cls($name . 'icon')->attr('src', $iconImage);
        }
        
        $item = $h->li()->content(
            $h->a()->cls($name . 'link')->href($target)->content(
                $h->span()->cls($name . 'label')->text($label),
                $icon
            )
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
