<?php
namespace qeywork;

class MenuVisualUsingLists implements IMenuVisual {
    /** @var HtmlBuilder */
    private $h;

    public function __construct(HtmlBuilder $h) {
        $this->h = $h;
    }

    public function menu($content, $id = null, $class = null) {

        $menu = $this->h->nav()->content($content);
        if ($class !== null) {
            $menu->cls($class);
        }
        if ($id !== null) {
            $menu->id($id);
        }
        return $menu;
    }
    
    protected function createLabel($label, $name) {
        return $this->h->span()->cls($name . 'label menu-item-label')->text($label);
    }
    
    protected function createIcon($iconImage, $name) {

        if ($iconImage != null) {
            return $this->h->img()->cls($name . 'icon menu-item-icon')->attr('src', $iconImage);
        }
        return new NullHtml();
    }
    
    protected function createLink($label, Url $target, $name, $iconImage) {
        $name = str_replace('/', '-', $name);
        return $this->h->a()->cls($name . 'link menu-item-link')->href($target)->content (
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
        

        
        $item = $this->h->li()->content(
            $this->createLink($label, $target, $name, $iconImage)
        );
        
        if ($class != null) {
            $item->cls($class);
        }
        
        if ($style != null) {
            $item->style($style);
        }
        
        if ($submenu != null) {
            $item->append($submenu->render($h));
        }
        
        return $item;
    }
    
    public function itemGroup($items, $class = null) {

        $group = $this->h->ul()->content($items);
        if ($class !== null) {
            $group->cls($class);
        }
        
        return $group;
    }
}
