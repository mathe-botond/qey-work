<?php
namespace QeyWork\View\Forms\Validators;

/**
 * @author klara
 */
abstract class Validator {
    protected $message;
    
    public function setMessage($failMessage) {
        $this->message = $failMessage;
        return $this;
    }
    
    public function getMessage() {
        return $this->message;
    }
    
    public abstract function getName();
    
    public abstract function validate($value);
}
