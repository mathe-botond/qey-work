<?php
namespace qeywork;

/**
 * @author Dexx
 */
class SimpleRenderebleArray extends SmartArray implements IRenderable {
    public function render() {
        $array = $this->getArrayCopy();
        for ($i = 0; $i < sizeof($array); $i++) {
            $array[$i] .= '';
        }
        return json_encode($array);
    }
}
