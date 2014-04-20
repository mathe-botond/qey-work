<?php
namespace qeywork;

/**
 * 
 * Url path handler
 * So I dont need to think about slashes anymore
 * Thanks a lot vera linn
 * @author Dexx
 * @method Path dir(string $directory) Append a single directory (parameter) 
 * @method Path addDir(string $directory) Same as dir($directory)
 * @method Path addDirs(array $dirList) Append directory list 
 * @method Path file(string $filename) Specify filename
 * TODO: continue
 */
class Path
{
    private $dirs;
    private $file;
    
    /**
     * Constructor of this class
     * @param array $dirs
     * @param string $file
     */
    public function __construct($dirs, $file = "")
    {
        if (is_string($dirs))
        {
            $token = '';
            for ($i = 0; $i < strlen($dirs); $i++)
            {
                $c = $dirs[$i];
                if ($c != '/' && $c != '\\') {
                    $token .= $c;
                } else {
                    $this->dirs[] = $token;
                    $token = '';
                }
            }
            if ($token !== '') {
                $this->dirs[] = $token;
            }
        }
        else if (is_array($dirs))
        {
            $this->dirs = $dirs;
        }
        $this->file = $file;
    }
    
    public function parentDir()
    {
        $dirs = $this->dirs;
        if (count($this->dirs) > 1) {
            array_pop($dirs);
        }

        return new Path($dirs, $this->file);
    }
    
    public function __call($name, $args)
    {
        $file = $this->file;
        $dirs = $this->dirs;
        if (isset($args[0])) {
            $value = $args[0];
        } else {
            $value = '';
        }

        switch ($name)
        {
            case 'dirs':
                $dirs = $value;
                break;
            case 'dir':
            case 'addDir':
                $dirs[] = $value; 
                break;
            case 'addDirs':
                $dirs = array_merge($dirs, $value);
                break;
            case 'file':
                $file = $value;
                break;
            default:
                trigger_error('Call to undefined method '.__CLASS__.'::'.$name);
        }
        
        $retVal = new Path($dirs, $file);
        return $retVal;
    }
    
    /**
     * Generates a string representing this path
     */
    public function toString()
    {
        $path = '';
        if (!empty($this->dirs)) {
            foreach ($this->dirs as $dir) {
                $path .= $dir . DIRECTORY_SEPARATOR;
            }
        }
        $path .= $this->file;
        return $path;
    }
    
    public function __toString()
    {
        return $this->toString();
    }
}