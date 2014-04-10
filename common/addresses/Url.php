<?php
namespace qeywork;

/**
 * Url path handler
 * So I dont need to think about slashes anymore
 * @author Dexx
 * @method dirs(array $dirList) Set directory (or parameter) list 
 * @method params(array $paramList) Set parameter (or directory) list
 * @method Url dir($directory) Append a single directory (parameter) 
 * @method Url addDir($directory) Same as dir($directory)
 * @method Url param($param) Append a single parameter (directory)
 * @method Url addParam($param) Same as param($param)
 * @method Url addDirs(array $dirList) Append directory (or parameter) list 
 * @method Url addParams(array $paramList) Append parameter (or directory) list
 * @method Url page($page) Specify page (or file)
 * @method Url file($file) Specify file (or page)
 * @method Url field($field,  $value = null) Specify file (or page)
 * TODO: continue, also do it for Path
 */
class Url
{
    private $domain;
    private $dirs;
    private $page;
    private $fields;
    
    /**
     * Constructor of this class
     * @param array $dirs
     * @param string $filename
     */
    public function __construct($path = null, $page = '', $fields = '')
    {
        if ($path == null) {
            return;
        }

        if (is_string($path))
        {
            $data = parse_url($path);            
            $this->domain = $data['scheme'] . '://' . $data['host'];
            
            $path = isset($data['path']) ? $data['path'] : '';
            $this->dirs = array_filter(explode('/', trim($path, '/')));
            $this->page = $page;
        }
        else
        {
            $data = $path;
            
            $this->domain = $path[0];
            $this->dirs = array();
            for ($i = 1; $i < count($data); ++$i) {
                $this->dirs[$i - 1] = $data[$i];
            }
            $this->page = $page;
        }
        
        $this->fields = array();
        $query = is_string($path) && array_key_exists('query', $data) ? $data['query'] : $fields;
        if ($query !== '')
        {
            $queryFields = split('[;&]', $data['query']);
            foreach ($queryFields as $field)
            {
                $fieldParts = explode('=', $field);
                $this->fields[$fieldParts[0]] = isset($fieldParts[1]) ? $fieldParts[1] : null;
            }
        }
    }
    
    public static function getCurrentDomain()
    {
        if ($_SERVER["SERVER_PORT"] != 80) {
            return "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } else {
            return "http://" . $_SERVER["SERVER_NAME"];
        }
    }
    
    public function parentDir()
    {
        $dirs = $this->dirs;
        if (count($this->dirs) > 0) {
            array_pop($dirs);
        }

        $url = clone($this);
        $url->dirs = $dirs;
        return $url;
    }
    
    public function __call($name, $args)
    {
        if (count($args) === 0) {
            throw new ArgumentException('At least one arg should be specified');
        }
        
        $page = $this->page;
        $dirs = $this->dirs;
        $domain = $this->domain;
        $value = $args[0];
        $fields = $this->fields;
        
        switch ($name)
        {
            case 'dirs':
            case 'params':
                $dirs = $value;
                break;
            case 'dir':
            case 'addDir':
            case 'param':
            case 'addParam':
                $dirs[] = $value; 
                break;
            case 'addDirs':
            case 'addParams':
                $dirs = array_merge($dirs, $value);
                break;
            case 'page':
            case 'file':
            case 'method':
                $page = $value;
                break;
            case 'domain':
                $domain = $value;
                break;
            case 'field':
                $fields[$args[0]] = isset($args[1]) ? $args[1] : null;
                break;
            default:
                trigger_error('Call to undefined method '.__CLASS__.'::'.$name);
        }
        
        $retVal = new Url();
        $retVal->domain = $domain;
        $retVal->dirs = $dirs;
        $retVal->page = $page;
        $retVal->fields = $fields;
        return $retVal;
    }
    
    public function getDirs() {
        return $this->dirs;
    }
    
    public function getPage() {
        return $this->page;
    }
    
    public function getFields() {
        return $this->fields;
    }
    
    /**
     * Generates a string representing this path
     */
    public function toString()
    {
        $path = $this->domain . '/';
        if (!empty($this->dirs)) {
            $path .= implode($this->dirs, '/') . '/';
        }
        $path .= $this->page;
        if (! empty($this->fields))
        {
            $path .= '?';
            $query = array();
            foreach ($this->fields as $name => $value)
            {
                $query[$name] = $name;
                if ($value != null) {
                    $query[$name] .= '=' . $value;
                }
            }
            $path .= implode('&', $query);
        }
        return $path;
    }
    
    public function __toString()
    {
        return $this->toString();
    }
}