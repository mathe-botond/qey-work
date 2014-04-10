<?php
namespace qeywork;

/**
 * @author Dexx
 */
abstract class Container implements IBlock {
    public function recursiveRender() {
        foreach ($this as $child) {
            if ($child instanceof IBlock) {
                $child->render();
            }
        }
    }
    
    public function render() {
        $this->recursiveRender();
    }
}
