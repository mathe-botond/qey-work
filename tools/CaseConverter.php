<?php
namespace qeywork;

/**
 * Description of CaseConverter
 *
 * @author Dexx
 */
class CaseConverter {
    const CASE_SEPARATOR = 0;
    const CASE_CAMEL = 1;
    const CASE_URL = 2;
    const CASE_UNDERSCORE = 3;
    const SIMPLE_TEXT = 4;

    const DELIMITER_URL = '-';
    const DELIMITER_UNDERSCORE = '_';
    
    protected $tokens;
    
    /**
     * @param string $input
     * @param int $caseFlag
     * @param string $param
     */
    public function __construct($input, $caseFlag, $param = '') {
        $input = trim($input);
        if (empty($input)) {
            $this->tokens = array();
            return ;
        }
        
        if ($caseFlag == self::SIMPLE_TEXT) {
            $this->tokens = preg_split('/\\s/', $input);
            foreach ($this->tokens as $key => $token) {
                $this->tokens[$key] = strtolower($token);
            }
            return ;
        }
        
        if ($caseFlag == self::CASE_CAMEL) {
            $input[0] = strtolower($input[0]);
            $len = strlen($input);
            $j = 0;
            $this->tokens[$j] = '';
            for ($i = 0; $i < $len; ++$i) {
                if (ord($input[$i]) >= ord('A') && ord($input[$i]) <= ord('Z')) {
                    $j++;
                    $this->tokens[$j] = '';
                    $input[$i] = strtolower($input[$i]);
                }
                $this->tokens[$j] .= $input[$i];
            }
            return ;
        } else {
            switch ($caseFlag) {
                case self::CASE_SEPARATOR:
                    $delimiter = $param;
                    break;
                case self::CASE_URL:
                    $delimiter = self::DELIMITER_URL;
                    break;
                case self::CASE_UNDERSCORE:
                    $delimiter = self::DELIMITER_UNDERSCORE;
                    break;
            }
            
            $this->tokens = explode($delimiter, $input);
        }  
    }
    
    public function toBeSeparatedWith($separator) {
        return implode($separator, $this->tokens);
    }
    
    public function toUrlCase() {
        $tokens = $this->tokens;
        foreach ($tokens as $key => $token) {
            if (! empty($token)) {
                $tokens[$key] = strtolower($token);
            }
        }
        
        return implode(self::DELIMITER_URL, $tokens);
    }
    
    public function toUnderscoredCase() {
        return $this->toBeSeparatedWith(self::DELIMITER_UNDERSCORE);
    }
    
    public function toCamelCase($firstLetterIsLower = true) {
        $tokens = $this->tokens;
        foreach ($tokens as $key => $token) {
            if (! empty($token)) {
                $tokens[$key][0] = strtoupper($token[0]);
            }
        }
        
        $result = implode('', $tokens);
        if ($firstLetterIsLower && !empty($result)) {
            $result[0] = strtolower($result[0]);
        }
        
        return $result;
    }
}
