<?php
namespace qeywork;

/**
 * Used when malformed model was detected 
 */
class ModelException extends \Exception {}

/**
 * Used when malformed model was detected 
 */
class DbException extends \Exception {}

/**
 * Used when there is a problem with a function's argument 
 */
class ArgumentException extends \Exception {}

/**
 * Used when there is a problem with a resource
 */
class ResourceException extends \Exception {}

/**
 * Used when a problem is detected with client side data 
 * (GET or POST data, cookies etc.).
 * ValidationException should be used for validations 
 */
class ClientDataException extends \Exception {}

/**
 * Throwed on validation error 
 */
class ValidationException extends \Exception {}

/**
 * \Exception used by the upload module 
 */
class UploadExceptions extends \Exception {}

/**
 * \Exception used by the upload module 
 */
class LocalizationException extends \Exception {}

/**
 * \Exception for the application.
 * All user defined exceptions should inherit from this class. 
 */
class ApplicationException extends \Exception {}

/**
 * \Exception thrown when user tries to access something he has no permission to
 */
class AuthorizationException extends \Exception {}

/**
 * \Exception used on bad redirects, and corrupted redirect data 
 */
class BadRedirectException extends \Exception {}

/**
 * Functionality not yet implemented
 */
class NotImplementedException extends \Exception {}

/**
 * Template related exception
 */
class TemplateException extends \Exception {}

/**
 * Template related exception
 */
class TypeException extends \Exception {
    public function __construct($variable, $typeExpected, $code = 0) {
        $type = gettype($variable);
        if ($type == 'object') {
            $type = get_class($variable);
        }
        $message = "$variable of type $typeExpected expected, $type given.";
        parent::__construct($message, $code);
    }
}

/**
 * Bad return type
 */
class ReturnValueException extends TypeException { }