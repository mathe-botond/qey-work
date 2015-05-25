<?php
namespace qeywork;

/**
 * @author Dexx
 */
class EmailValidator extends Validator {
    const NAME = 'E-mail';
    const DEFAULT_MESSAGE = 'E-mail address invalid';
    
    public function __construct($message = self::DEFAULT_MESSAGE) {
        $this->setMessage($message);
    }
    
    public function getName() {
        return self::NAME;
    }

    public function validate($value) {
        return preg_match('/^\S+@\S+\.\S+$/i', $value) == 1;
    }
}
