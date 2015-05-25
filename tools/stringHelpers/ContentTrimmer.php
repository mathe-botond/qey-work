<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ContentTrimmer {
    public function trim($text, $length) {
        $length = min(array($length, strlen($text)));
        $text = substr($text, 0, $length);
        
        for ($pos = $length-1; $pos >= 0; $pos--) {
            if (preg_match('/\s/', $text[$pos])) {
                break;
            }
        }
        
        return substr($text, 0, $pos) . '...';
    }
}
