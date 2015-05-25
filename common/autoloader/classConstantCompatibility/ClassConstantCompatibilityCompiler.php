<?php
namespace qeywork;

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
    
    const DEFAULT_OUTPUT = '.generated';
    
    const NS_SEP = "\\";
    
    public function __construct($outputDir = self::DEFAULT_OUTPUT) {
        $this->output = $outputDir;
    }
    
    private $files;
    private $output = null;

    public function addFile($directory, $filename) {
        $ending = self::FILE_PREC_ENDING . self::FILE_PHP_ENDING;
        if (! endsWith($filename, $ending)) {
            throw new \Exception(self::EXCEPTION_BAD_ENDING . $ending);
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

    public function processClassName($className, $namespace, $usages) {
        $pos = strpos($className, self::NS_SEP);
        if ($pos === 0) {
            return $className;
        }
        
        if ($pos === false && ! empty($namespace)) {
            return self::NS_SEP . $namespace . self::NS_SEP . $className;
        }
        
        $nsRewrite = substr($className, 0, $pos);
        if (array_key_exists($nsRewrite, $usages)) {
            $rewriteWith = $usages[$nsRewrite];
            return self::NS_SEP . $rewriteWith . substr($className, $pos);
        }
        
        return $className;
    }

    public function compile() {
        foreach ($this->files as $filename => $directory) {
            if (!file_exists($directory . DIRECTORY_SEPARATOR . $filename)) {
                unset($this->files[$filename]);
                continue;
            }
            $directory = rtrim($directory, DIRECTORY_SEPARATOR);
            
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
            
            $parser = new PhpNamespaceParser();
            $provider = new StringCodeProvider($content);
            $namespace = $parser->extractNamespace($provider);
            $provider->reset();
            $usages = $parser->extractUses($provider);
        
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
                $className = $this->processClassName($className, $namespace, $usages);
                $slashCorrectedClassName = str_replace('\\', '\\\\', $className);
                $result .= substr($entry, 0, $nameStart) 
                    . self::QUOTE . $slashCorrectedClassName . self::QUOTE;
            }
            
            $result .= end($splitContent);
            
            if (!is_dir($output)) {
                mkdir($output, 0777, true);
            }
            file_put_contents($output . DIRECTORY_SEPARATOR . $newFile, $result);
        }
    }
}
