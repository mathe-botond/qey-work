<?php
namespace QeyWork\Entities\Fields;
use QeyWork\Entities\IEntityType;
use QeyWork\View\Html\HtmlBuilder;
use QeyWork\View\Html\IHtmlObject;

/**
 * A Field is a member of a Entity.
 *
 * @author Dexx
 */
class Field implements IEntityType, IHtmlObject {
    protected $name;
    protected $value;
    
    public function __construct($name) {
        $this->name = $name;
    }
    
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }
    
    public function value() {
        return $this->value;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function isEmpty() {
        $value = $this->value();
        return empty($value);
    }

    public function __toString() {
        return $this->toString();
    }

    public function render(HtmlBuilder $h) {
        return $this;
    }

    public function toString() {
        return $this->value() . '';
    }
}
