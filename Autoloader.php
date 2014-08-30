<?php
namespace qeywork;

class AutoloaderException extends \Exception {}

define('NAMESPACE_SEPARATOR', '\\');

/**
 * Autoload classes
 *
 * @author Dexx
 */
class SvnFilterIterator extends \RecursiveFilterIterator {
    public static $filters = array(
        '.svn',
    );

    public function accept() {
        return !in_array(
            $this->current()->getFilename(),
            self::$filters,
            true
        );
    }
}

class CustomFilterIterator extends \RecursiveFilterIterator {
    public static $filters = array();
    
    public function __construct($recursiveIterator, $filters = null) {
        parent::__construct($recursiveIterator);
        if ($filters !== null) {
            self::$filters = $filters;
        }
    }

    public function accept() {
        return !in_array(
            $this->current()->getFilename(),
            self::$filters,
            true
        );
    }
}
 
class Autoloader {
    private $classList = null;
    private $path;
    private $file;
    private $key;
    private $status = 1;
    
    private static $instances = array();
    
    public function init() {
        if (file_exists($this->file)) {
            $content = file_get_contents($this->file);
            $this->classList = unserialize($content);
        } else {
            $this->gatherClassFiles();
            file_put_contents($this->file, serialize($this->classList));
        }
    }
    
    public function __construct($path, $key) {
        $this->key = $key;
        $this->path = $path;
        $this->file = $path . '/' . '.autoloader.config';
        
        self::$instances[$key] = $this;
        
        $this->init();
        
        spl_autoload_register(array($this, 'load'));
    }
    
    public function getKey() {
        return $this->key;
    }
    
    private function reset() {
        if (file_exists($this->file)) {
            unlink($this->file);
        }
        $this->init();
    }
    
    private function extractNamespace(\SplFileInfo $file) {
        $f = $file->openFile();
        $inPhp = false;
        $matches = array();
        while ($line = $f->fgets()) {
            if (($pos = strpos($line, '<?php')) !== false) {
                $inPhp = true;
                //remove <?php and everything before it from this line
                //because the namespace clause might be in this exact same line
                $line = substr($line, $pos + 5);
            }
            if ($inPhp) {
                if (preg_match('/\\s*namespace\\s+([\\w\\d\\\\]+)\\s*;/', $line, $matches)) {
                    $namespace = $matches[1];
                    return $namespace;
                }
            }
        }
    }
    
    protected function gatherClassFiles() {
        $dirIt = new \RecursiveDirectoryIterator($this->path);
		$filterIterator = new CustomFilterIterator($dirIt, array_merge(SvnFilterIterator::$filters, array('js')));
        $it = new \RecursiveIteratorIterator($filterIterator, \RecursiveIteratorIterator::SELF_FIRST);
        $this->classList = array();
        foreach($it as $file) {
            /* @var $file \SplFileInfo */
            $filename = $file->getFilename();
            
            $firstCharacter = $filename !== '' ? $filename[0] : '';
            if (substr($filename, -4) === '.php' && $firstCharacter >= 'A' && $firstCharacter <= 'Z') {
                $pathinfo = pathinfo($filename);
                $classname = $pathinfo['filename'];
                if (isset($this->classList[$classname])) {
                    throw new AutoloaderException('Multiple class definitions at: '
                            . $this->classList[$classname] . ' and '
                            . $file);
                } else { 
                    $absolutePath = $file->getPath();
                    $relativePath = str_replace($this->path, '', $absolutePath);
                    $explodedRelativePath = explode(DIRECTORY_SEPARATOR, trim($relativePath, DIRECTORY_SEPARATOR));
                    $namespace = $this->extractNamespace($file);
                    $this->classList[$namespace . NAMESPACE_SEPARATOR . $classname] = array(
                        'path' => $explodedRelativePath,
                        'file' => $filename
                    );
                }
            }
        }
    }
    
    protected static function checkAllInstanceIntegrity() {
        $status = 0;
        foreach (self::$instances as $loader) {
            $status += $loader->status;
        }
        
        if ($status === 0) { //all loaders failed to find missing class
            foreach (self::$instances as $loader) {
                $loader->reset();
            }
            return true;
        }
        
        return false;
    }
    
    public function load($class) {
        $result = true;
        if (! isset($this->classList[$class])) {
            $this->status = 0;
            $result = self::checkAllInstanceIntegrity();
        }
        
        if ($result && isset($this->classList[$class])) {
            $file = $this->path
                    . DIRECTORY_SEPARATOR
                    . implode(DIRECTORY_SEPARATOR, $this->classList[$class]['path'])
                    . DIRECTORY_SEPARATOR
                    . $this->classList[$class]['file'];
            
            if (! file_exists($file)) {
                $this->reset();
            }

            require_once($file);
            //$class::$classname = $class;
        }
    }
    
    public function getRelativePathToClass($class) {
        if (! isset($this->classList[$class])) {
            throw new AutoloaderException("Don't know any class named '$class'");
        }
        return $this->classList[$class]['path'];
    }
    
    public function getPath() {
        return $this->path;
    }
}
