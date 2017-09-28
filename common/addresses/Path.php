<?php
namespace QeyWork\Common\Addresses;

/**
 * 
 * Url path handler
 * So I dont need to think about slashes anymore
 * Thanks a lot vera linn
 * @author Dexx
 */
class Path extends AbstractPath
{    
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
    
    protected function getCopy() {
        return new Path($this->dirs, $this->file);
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