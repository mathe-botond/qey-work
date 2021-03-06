<?php
namespace QeyWork\Common\Addresses;

/**
 * Url path handler
 * So I dont need to think about slashes anymore
 * @author Dexx
 * TODO: continue, also do it for Path
 */
class Url extends AbstractPath
{
    public $domain;
    public $fields;
    public $hash;

    /**
     * Constructor of this class
     * @param null $path
     * @param string $page
     * @param string $fields
     * @internal param array $dirs
     * @internal param string $filename
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
            $this->file = $page;
        }
        else
        {
            $data = $path;
            
            $this->domain = $path[0];
            $this->dirs = array();
            for ($i = 1; $i < count($data); ++$i) {
                $this->dirs[$i - 1] = $data[$i];
            }
            $this->file = $page;
        }
        
        $this->fields = array();
        $query = is_string($path) && array_key_exists('query', $data) ? $data['query'] : $fields;
        if ($query !== '')
        {
            $queryFields = preg_split('/[;&]/', $data['query']);
            foreach ($queryFields as $field)
            {
                $fieldParts = explode('=', $field);
                $this->fields[$fieldParts[0]] = isset($fieldParts[1]) ? $fieldParts[1] : null;
            }
        }
    }
    
    public static function getCurrentDomain(array $serverVar)
    {
        if (isset($serverVar["SERVER_PORT"]) && $serverVar["SERVER_PORT"] != 80) {
            return "http://" . $serverVar["SERVER_NAME"] . ":" . $serverVar["SERVER_PORT"];
        } else {
            return "http://" . $serverVar["SERVER_NAME"];
        }
    }

    public static function getDomainUrl(array $serverVar) {
        return new Url(self::getCurrentDomain($serverVar));
    }
    
    protected function getCopy() {        
        $retVal = new Url();
        $retVal->domain = $this->domain;
        $retVal->dirs = $this->dirs;
        $retVal->file = $this->file;
        $retVal->fields = $this->fields;
        return $retVal;
    }
    
    public function params(array $params) {
        return $this->dirs($params);
    }
    
    public function param($param) {
        return $this->dir($param);
    }
    
    public function addParam($param) {
        return $this->dir($param);
    }
    
    public function addParams(array $params) {
        return $this->addDirs($params);
    }
    
    public function page($page) {
        return $this->file($page);
    }
    
    public function domain($domain) {
        $copy = $this->getCopy();
        $copy->domain = $domain;
        return $copy;
    }
    
    public function field($name, $value) {
        $copy = $this->getCopy();
        $copy->fields[$name] = $value;
        return $copy;
    }

    public function addFields($fields) {
        $copy = $this->getCopy();
        $copy->fields = $copy->fields ? $copy->fields : [];
        $copy->fields = array_merge($copy->fields, $fields);
        return $copy;
    }
    
    public function getPage() {
        return $this->file;
    }
    
    public function getFields() {
        return $this->fields;
    }
    
    public function setHash($hash) {
        $copy = $this->getCopy();
        $copy->hash = trim($hash, '#');
        return $copy;
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
        $path .= $this->file;
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
        
        if (!empty($this->hash)) {
            $path .= '#' . $this->hash;
        }
        
        return $path;
    }
    
    public function __toString()
    {
        return $this->toString();
    }
}