<?php
namespace qeywork;

/**
 * Autoload classes
 *
 * @author Dexx
 */
class SvnFilterIterator extends \RecursiveFilterIterator {
    public static $FILTERS = array(
        '.svn',
    );

    public function accept() {
        return !in_array(
            $this->current()->getFilename(),
            self::$FILTERS,
            true
        );
    }
}

class CustomFilterIterator extends \RecursiveFilterIterator {
    public static $FILTERS = array();
    
    public function __construct($recursiveIterator, $filters = null) {
        parent::__construct($recursiveIterator);
        if ($filters !== null) self::$FILTERS = $filters;
    }

    public function accept() {
        return !in_array(
            $this->current()->getFilename(),
            self::$FILTERS,
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
    
    public static function createInstance($namespace, $path, $key) {
        self::$instances[$key] = new Autoloader($namespace, $path, $key);
    }
    
    public function init() {
        if (file_exists($this->file)) {
            $content = file_get_contents($this->file);
            $this->classList = unserialize($content);
        } else {
            $this->gatherClassFiles();
            file_put_contents($this->file, serialize($this->classList));
        }
    }
    
    private function __construct($namespace, $path, $key) {
        $this->key = $key;
        $this->path = $path;
        $this->namaspace = $namespace;
        $this->file = $path . '/' . '.autoloader.config';
        
        $this->init();
        
        spl_autoload_register(array($this, 'load'));
    }
    
    private function reset() {
        if (file_exists($this->file)) {
            unlink($this->file);
        }
        $this->init();
    }
    
    private function gatherClassFiles() {
        $dirIt = new \RecursiveDirectoryIterator($this->path);
		$filterIterator = new CustomFilterIterator($dirIt, array_merge(SvnFilterIterator::$FILTERS, array('js')));
        $it = new \RecursiveIteratorIterator($filterIterator, \RecursiveIteratorIterator::SELF_FIRST);
        $this->classList = array();
        foreach($it as $file) {
            $filename = $file->getFilename();
            
            $fc = $filename !== '' ? $filename[0] : '';
            if (substr($filename, -4) === '.php' && $fc >= 'A' && $fc <= 'Z') {
                $pathinfo = pathinfo($filename);
                $filename = $pathinfo['filename'];
                if (isset($this->classList[$filename])) {
                    throw new \Exception('Multiple class definitions at: '
                            . $this->classList[$filename] . ' and '
                            . $file);
                } else {
                    $this->classList[$filename] = (string)$file;
                }
            }
        }
    }
    
    private static function checkAllInstanceIntegrity($namespace) {
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
            if (! file_exists($this->classList[$class])) {
                $this->reset();
            }

            require_once($this->classList[$class]);
        }
    }
}
?>
