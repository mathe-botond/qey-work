<?php
namespace qeywork;

class XssFilter implements ValueFilter
{
    const HTML_TAGS = 1;
    const QUOTES = 2;
    const JS_LINKS = 4;
    const ALL = -1;
    
    protected $mode;

    public function __construct($mode = XssFilter::ALL) {
        $this->mode = $mode;
    }
    
    public function execute($value) {
        if (is_string($value))
        {
            if ($this->modes & Security::HTML_TAGS)
                $value = htmlspecialchars($value);
            
            if ($this->modes & Security::QUOTES)
                $value = htmlentities($value);
            
            if ($this->modes & Security::JS_LINKS)
                $value = str_replace('javascript:', 'javascript&#58;', $value);
            
            return $value;
        }
        else
        {
            //TODO:: handle for <input name='name[]' /> inputs
            return $value;
        }
    }

    public function getName() {
        
    }
}
?>