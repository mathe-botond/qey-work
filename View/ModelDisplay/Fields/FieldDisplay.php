<?php
namespace QeyWork\View\ModelDisplay\Fields;
use QeyWork\Entities\Fields\Field;
use QeyWork\View\Html\HtmlBuilder;
use QeyWork\View\IRenderable;

/**
 * @author Dexx
 */
abstract class FieldDisplay implements IRenderable {
    protected $field;
    protected $visible = true;
    
    public $label;
    
    public function __construct(Field $field) {
        $this->field = $field;
    }
    
    public function hide() {
        $this->visible = false;
    }
    
    public function show() {
        $this->visible = true;
    }
    
    public function setLabel($label) {
        $this->label = $label;
    }

    public function isVisible() {
        return $this->visible;
    }
    
    public function getName() {
        return $this->field->getName();
    }
    
    public abstract function render(HtmlBuilder $h);
}
