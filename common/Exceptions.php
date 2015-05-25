<?php
namespace qeywork;

/**
 * Use when testing reference against null 
 */
class NullRefenceException extends \Exception {}

/**
 * Used when malformed entity was detected
 */
class EntityException extends \Exception {}

/**
 * Used when malformed entity was detected
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
 * Used when there is a problem with routing
 */
class RouteException extends \Exception {}

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
class ReturnValueException extends \Exception {
    public function __construct($method, $typeExpected, $variable, $code = 0) {
        $type = gettype($variable);
        if ($type == 'object') {
            $type = get_class($variable);
        }
        $message = "$method should have returned $typeExpected, $type given.";
        parent::__construct($message, $code);
    }
}