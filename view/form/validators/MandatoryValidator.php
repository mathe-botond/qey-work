<?php
namespace qeywork;

/**
 * @author Dexx
 */
class MandatoryValidator extends Validator {
    const VALIDATOR_NAME = 'mandatory';
    const VALIDATOR_MESSAGE = 'This field is mandatory';
    
    public function __construct() {
        $this->message = self::VALIDATOR_MESSAGE;
    }
    
    public function validate($value) {
        return ! empty($value);
    }

    public function getName() {
        return self::VALIDATOR_NAME;
    }
}
