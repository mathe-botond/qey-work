<?php
namespace qeywork;

/**
 * @author Dexx
 */
class MenuItem extends MenuEntity {
    /** @var string */
    protected $label;
    /** @var Path */
    protected $link;
    /** @var MenuEntityCollection */
    protected $children;
    /** @var string */
    public $class;
    /** @var URL */
    public $iconImage;
    
    public function __construct($label, Url $link, MenuEntityCollection $children = null) {
        $this->label = $label;
        $this->link = $link;
        $this->children = $children;
    }
    
    public function getLabel() {
        return $this->label;
    }
    
    public function getLink() {
        return $this->link;
    }
    
    public function getChildren() {
        return $this->children;
    }
    
    public function setChildCotainer(MenuEntityCollection $container) {
        $this->children = $container;
    }
    
    public function addChild(MenuEntity $child) {
        if ($this->children == null) {
            throw new BadFunctionCallException('Child cotainer is null. Call setChildCotainer.');
        }
        
        $this->children->add($child);
    }
}
