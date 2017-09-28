<?php
namespace QeyWork\Common;
use QeyWork\View\Html\HtmlBuilder;
use QeyWork\View\IRenderable;

/**
 * @author Dexx
 */
class SimpleRenderebleArray extends SmartArray implements IRenderable {
    public function render(HtmlBuilder $h) {
        $array = $this->getArrayCopy();
        for ($i = 0; $i < sizeof($array); $i++) {
            $array[$i] .= '';
        }
        return json_encode($array);
    }
}
