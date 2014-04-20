<?php
namespace qeywork;

class AutoloaderException extends \Exception {};

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
    private $namaspace;
    
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
    
    public function __construct($namespace, $path, $key) {
        $this->key = $key;
        $this->path = $path;
        $this->namaspace = $namespace;
        $this->file = $path . '/' . '.autoloader.config';
        
        self::$instances[$key] = $this;
        
        $this->init();
        
        spl_autoload_register(array($this, 'load'));
    }
    
    private function reset() {
        if (file_exists($this->file)) {
            unlink($this->file);
        }
        $this->init();
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
                    $this->classList[$classname] = array(
                        'path' => $explodedRelativePath,
                        'file' => $filename
                    );
                }
            }
        }
    }
    
    protected static function checkAllInstanceIntegrity($namespace) {
        $status = 0;
        foreach (self::$instances as $loader) {
            /* @var $loader Autoloader */
            if ($namespace !== null && $loader->namaspace === $namespace) {
                $status += $loader->status;
            }
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
        $namespace = null;
        
        if (false !== ($lastNsPos = strripos($class, '\\'))) {
            $namespace = substr($class, 0, $lastNsPos);
            if ($namespace != $this->namaspace) {
                return;
            }
            
            $class = substr($class, $lastNsPos + 1);
        }
        
        if (! isset($this->classList[$class])) {
            $this->status = 0;
            $result = self::checkAllInstanceIntegrity($namespace);
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
        $lastNsPos = strripos($class, '\\');
        $class = substr($class, $lastNsPos + 1);
        if (! isset($this->classList[$class])) {
            throw new AutoloaderException("Don't know any class named '$class'");
        }
        return $this->classList[$class]['path'];
    }
    
    public function getPath() {
        return $this->path;
    }
}
