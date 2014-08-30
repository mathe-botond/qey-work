<?php
namespace qeywork;

class Params 
{ 
    const ALL      = 0;
    const GET      = 1;
    const POST     = 2;
    const COSTUM   = 3;
    
    const TARGET = '_target';
    
    private $alias;
    private $method;
    
    public $trimmed = true;
    
    protected $args = array();
    protected $target = null;
    
    public function shiftTarget() {
        if (count($this->args) >= 1) {
            $this->target = array_shift($this->args);
        } else {
            $this->target = null;
        }
    }
    
    public function __construct($method = self::GET, array $alias = array())
    {
        $this->setMethod($method, $alias);
        
        $this->args = explode('/', trim( $alias[self::TARGET] , '/'));
        
        $this->shiftTarget();
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function setMethod($method, array $alias = array())
    {
        $this->method = $method;
        switch ($this->method)
        {
            case Params::GET:
                $this->alias = $_GET;
                break;
            
            case Params::POST:
                $this->alias = $_POST;
                break;
            
            case Params::ALL:
                $this->alias = $_REQUEST;
                break;
            
            case Params::COSTUM:
                $this->alias = $alias;
                break;
            
            default:
                throw new ArgumentException("No method defined for: " . $this->method);
        }
    }
    
    public function exists($name)
    {
        return isset($this->alias[$name]);
    }
    
    public function get($name)
    {
        $converter = new CaseConverter($name, CaseConverter::CASE_CAMEL);
        $name = $converter->toUrlCase();
        if (isset($this->alias[$name])) {
            $retVal = $this->alias[$name];
            if ($this->trimmed && is_string($retVal)) {
                $retVal = trim($retVal);
            }
            return $retVal;
        } else {
            throw new ClientDataException("Parameter '$name' does not exist");
        }
    }
    
    public function __get($name) {
        return $this->get($name);
    }
	
    public function getRequestedTarget()
    {
        return $this->target;
    }
    
    public function getArgs()
    {
        return $this->args;
    }
    
    public function getFormDataAsModel(FormData $form)
    {
        foreach ($form->getFields() as $field) {
            if ($field instanceof FormField) {
                $key = $field->getName();
                if ( $this->exists($key) ) {
                    $field->setValue($this->$key);
                }
            }
        }
    }
}
