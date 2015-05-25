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
        '.svn', '.git'
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
    protected $classList = null;
    protected $path;
    protected $file;
    private $key;
    private $status = 1;
    
    private static $instances = array();
    private static $firstError = true;
    
    private $namespaceExtractor;

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
        $this->namespaceExtractor = new PhpNamespaceParser();
        
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
        $this->status = 1;
    }
    
    protected function gatherClassFiles() {
        clearstatcache();
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
                    $namespace = $this->namespaceExtractor->extractNamespace(
                            new PhpFileCodeProvider($file));
                    $this->classList[$namespace . NAMESPACE_SEPARATOR . $classname] = array(
                        'path' => $explodedRelativePath,
                        'file' => $filename
                    );
                }
            }
        }
    }
    
    protected static function checkAllInstanceIntegrity($class) {
        $status = 0;
        foreach (self::$instances as $loader) {
            $status += $loader->status;
        }
        
        if ($status === 0) { //all loaders failed to find missing class
            foreach (self::$instances as $loader) {
                if (self::$firstError) {
                    self::$firstError = false;
                    echo "Can't find '$class'. Reset.</br>";
                    $e = new \Exception();
                    echo($e->getTraceAsString());
                }
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
            $result = self::checkAllInstanceIntegrity($class);
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
    
    public function unregister() {
        spl_autoload_unregister(array($this, 'load'));
    }
}
