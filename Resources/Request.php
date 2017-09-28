<?php
namespace QeyWork\Resources;

use QeyWork\Common\ArgumentException;
use QeyWork\Common\ClientDataException;
use QeyWork\Common\Globals;
use QeyWork\Tools\StringHelpers\CaseConverter;
use QeyWork\View\Forms\Fields\FormData;
use QeyWork\View\Forms\Fields\FormField;

class Request
{

    /**
     * @var Globals
     */
    private $globals;

    const ALL      = 0;
    const GET      = 1;
    const POST     = 2;
    const COSTUM   = 3;
    
    
    private $alias;
    private $method;
    
    public $trimmed = true;
    
    protected $args = array();
    protected $target = null;
    
    public function __construct(Globals $globals, $method = self::ALL)
    {
        $this->globals = $globals;        
        $this->setMethod($method);
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
            case Request::GET:
                $this->alias = $this->globals->getGlobal(Globals::KEY_GET);
                break;
            
            case Request::POST:
                $this->alias = $this->globals->getGlobal(Globals::KEY_POST);
                break;
            
            case Request::ALL:
                $this->alias = $this->globals->getGlobal(Globals::KEY_REQUEST);
                break;
            
            case Request::COSTUM:
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

    /**
     * @param string $name
     * @throws ClientDataException
     * @return string
     */
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
    
    public function getArgs()
    {
        return $this->args;
    }
    
    public function getFormDataAsEntity(FormData $form)
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

    public function isPosted() {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }
}
