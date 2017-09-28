<?php
namespace QeyWork\View\Forms\Validators;

/**
 * @author Dexx
 */
class MandatoryValidator extends Validator {
    const VALIDATOR_NAME = 'mandatory';
    const DEFAULT_MESSAGE = 'This field is mandatory';
    
    public function __construct($message = self::DEFAULT_MESSAGE) {
        $this->setMessage($message);
    }
    
    public function validate($value) {
        return ! empty($value);
    }

    public function getName() {
        return self::VALIDATOR_NAME;
    }
}
