<?php
namespace qeywork;

require_once 'common.php';

/**
 * Rewrites ::class constants to quoted class names
 *
 * @author Dexx
 */
class ClassConstantCompatibilityCompiler {
    const FILE_PREC_ENDING = '.prec';
    const FILE_PHP_ENDING = '.php';
    const EXCEPTION_BAD_ENDING = 'Precompiled source file must end with: ';
    const QUOTE = "'";
    const CLASS_CONSTANT = '::class';
    const CLASS_NAME_CHARACTER_REGEX = '/[a-z0-9\\\\]/i';
    const PHP_START_TAG = '<?php';
    const RESULT_FILE_COMMENT = "/** NOTICE: This is a generated document. / 
Any edits will be removed. /
Please edit the corresponding .prec.php file, from which this /
document was created. */";
    
    private $files;
    private $output = null;

    public function addFile($directory, $filename) {
        $ending = self::FILE_PREC_ENDING . self::FILE_PHP_ENDING;
        if (! endsWith($filename, $ending)) {
            throw new Exception(self::EXCEPTION_BAD_ENDING . $ending);
        }
        $this->files[$filename] = $directory;
    }
    
    public function setOutputDir($dir) {
        $this->output = $dir;
        
        if (! is_dir($dir)) {
            // dir doesn't exist, make it
            mkdir($dir);
        }
    }
    
    public function compile() {
        foreach ($this->files as $filename => $directory) {
            if ($this->output != null) {
                $output = $this->output;
            } else {
                $output = $directory;
            }
            
            $newFile = str_replace(self::FILE_PREC_ENDING, '', $filename);
            if (file_exists($output . DIRECTORY_SEPARATOR . $newFile) 
                    && filemtime($directory . DIRECTORY_SEPARATOR . $filename) <
                    filemtime($output . DIRECTORY_SEPARATOR . $newFile)) {
                continue;
            }
            
            $content = file_get_contents($directory . DIRECTORY_SEPARATOR . $filename);
            $splitContent = explode(self::CLASS_CONSTANT, $content);
            $result = '';
            for ($i = 0; $i < count($splitContent) - 1; $i++) {
                $entry = $splitContent[$i];
                $size = strlen($entry);
                for ($nameStart = $size-1; $nameStart > 0; $nameStart--) {
                    if (! preg_match(self::CLASS_NAME_CHARACTER_REGEX, $entry[$nameStart])) {
                        $nameStart++;
                        break;
                    }
                }
                $className = substr($entry, $nameStart);
                $slashCorrectedClassName = str_replace('\\', '\\\\', $className);
                $result .= substr($entry, 0, $nameStart) 
                    . self::QUOTE . $slashCorrectedClassName . self::QUOTE;
            }
            
            $result .= end($splitContent);
                    
            $pos = strpos(self::PHP_START_TAG, $result);
            if ($pos !== false) {
                $tagSize = strlen(self::PHP_START_TAG);
                $result = substr($result, 0, $tagSize)
                        . self::RESULT_FILE_COMMENT
                        . substr($result, $tagSize + 1);
            }
            
            file_put_contents($output . DIRECTORY_SEPARATOR . $newFile, $result);
        }
    }
}
