<?php
namespace QeyWork\Common\Routers;
use QeyWork\Tools\StringHelpers\CaseConverter;

/**
 * @author Dexx
 */
class ClassNameRouter implements IContentRouter, IActionRouter {
    protected $namespace;
    
    public function __construct($namespace) {
        $this->namespace = $namespace;
    }
    
    protected function getTarget($name) {
        $converter = new CaseConverter($name, CaseConverter::CASE_URL);
        $class = $this->namespace . '\\' . $converter->toCamelCase(false);
        return new $class();
    }
    
    public function getPage($name) {
        return $this->getTarget($name);
    }

    public function getAction($name) {
        return $this->getTarget($name);
    }
}
