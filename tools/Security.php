<?php
namespace qeywork;

class Security
{
    const HTML_TAGS = 1;
    const QUOTES = 2;
    const JS_LINKS = 4;
    const ALL = -1;
    
    static function xssSenitize($input, $modes = 5)
    {
        if (is_string($input))
        {
            if ($modes & Security::HTML_TAGS)
                $input = htmlspecialchars($input);
            
            if ($modes & Security::QUOTES)
                $input = htmlentities($input);
            
            if ($modes & Security::JS_LINKS)
                $input = str_replace('javascript:', 'javascript&#58;', $input);
            
            return $input;
        }
        else
        {
            //TODO:: handle for <input name='name[]' /> inputs
            return $input;
        }
    }
    
    static function xsrfGenerateToken($id, $salt = 'default', $length = 6)
    {
        $token = hash(session_id() . $salt);
        $token = substr($token, (40 - $length) / 3, $length);
        return $token;
    }
}
?>