<?php
namespace qeywork;

/**
 * @author Dexx
 */
class NumericValidator extends Validator {
    const NAME = 'numeric';
    const DEFAULT_MESSAGE = 'Not a number';
    
    public function __construct($message = self::DEFAULT_MESSAGE) {
        $this->setMessage($message);
    }

    public function getName() {
        return self::NAME;
    }

    public function validate($value) {
        return is_numeric($value);
    }
}
