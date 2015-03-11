<?php
namespace qeywork;

/**
 * @author Dexx
 */
class Container implements IBlock {
    protected $children = array();
    
    public function add($name, IRenderable $child) {
        $this->children[$name] = $child;
    }
    
    public function addMultiple(array $children) {
        foreach ($children as $name => $child) {
            $this->add($name, $child);
        }
    }
    
    public function recursiveRender() {
        $output = new HtmlEntityList();
        foreach ($this->children as $child) {
            $output->add($child->render());
        }
        if ($output->count() == 1) {
            return $output[0];
        } else {
            return $output;
        }
    }
    
    public function getChildren() {
        return $this->children;
    }
    
    public function getChild($name) {
        if (! array_key_exists($name, $this->children)) {
            throw new \BadMethodCallException("Container doesn't have a child named '$name'");
        }
        return $this->children[$name];
    }
    
    /**
     * @return IHtmlEntity
     */
    public function render() {
        return $this->recursiveRender();
    }
}
