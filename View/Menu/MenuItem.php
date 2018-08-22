<?php
namespace qeywork;

/**
 * @author Dexx
 */
class MenuItem implements IMenuEntity {
    /** @var string */
    protected $label;
    /** @var Path */
    protected $link;
    /** @var MenuEntityCollection */
    protected $children;
    /** @var string */
    public $class = null;
    /** @var string */
    public $style = null;
    /** @var URL */
    public $iconImage;
    /** @var string */
    protected $token = '';
    protected $parent;

    public function __construct($token, $label, Url $link, MenuEntityCollection $children = null) {
        $this->token = $token;
        $this->label = $label;
        $this->link = $link;
        $this->children = $children;
    }
    
    public function setLink(Url $link) {
        $this->link = $link;
    }
    
    public function getToken() {
        return $this->token;
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
    
    public function setChildContainer(MenuEntityCollection $container) {
        $this->children = $container;
        $container->setParent($this);
    }
    
    public function addChild(IMenuEntity $child) {
        if ($this->children == null) {
            throw new \BadFunctionCallException('Child cotainer is null. Call setChildCotainer.');
        }
        
        $this->children->add($child);
    }
    
    public function setParent(MenuEntityCollection $parent) {
        $this->parent = $parent;
    }
    
    /**
     * @return MenuEntityCollection
     */
    public function getParent() {
        return $this->parent;
    } 
}
