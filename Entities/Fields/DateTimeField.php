<?php
/**
 * Author: Mathe E. Botond
 */

namespace QeyWork\Entities\Fields;


use QeyWork\Common\ArgumentException;

class DateTimeField extends Field {
    public $format = "Y-m-d H:i:s";

    /** @var \DateTime */
    protected $value;

    public function setNow() {
        $this->setValue(new \DateTime());
    }

    public function getDateTime() {
        return $this->value;
    }

    public function setValue($value) {
        if (is_string($value)) {
            $this->value = new \DateTime($value);
        } else if ($value instanceof \DateTime) {
            $this->value = $value;
        } else {
            throw new ArgumentException('Invalid date type');
        }

        return $this;
    }

    public function value() {
        return $this->value->format($this->format);
    }
}
