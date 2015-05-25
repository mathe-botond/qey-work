<?php
namespace qeywork;

/**
 * Pathbasis is used as a basis for Paths and Urls implementation
 *
 * @author Dexx
 */
abstract class AbstractPath implements IRenderable {
    public $dirs = array();
    public $file;
    
    /**
     * @return AbstractPath
     */
    protected abstract function getCopy();
    
    /**
     * Creates a new instance to the parent directory of this
     * @return type
     */
    public function parentDir()
    {
        $copy = $this->getCopy();
        if (count($copy->dirs) > 0) {
            array_pop($copy->dirs);
        }

        return $copy;
    }
    
    /**
     * Returns the directory list from the path
     * @return array Directory list
     */
    public function getDirs() {
        return $this->dirs;
    }

    /**
     * Returns the file form the path
     * @return string Filename
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * Creates a new instance with the provided directory list
     * @param array $dirs
     * @return AbstractPath
     */
    public function dirs(array $dirs) {
        $copy = $this->getCopy();
        $copy->dirs = $dirs;
        return $copy;
    }
    
    /**
     * Creates a new instance with the provided directory added to the path
     * @param string $directory
     * @return AbstractPath
     */
    public function dir($directory) {
        $copy = $this->getCopy();
        $copy->dirs[] = $directory;
        return $copy;
    }
    
    /**
     * Same as dir()
     * @param string $directory
     * @return AbstractPath
     */
    public function addDir($directory) {
        return $this->dir($directory);
    }
    
    /**
     * Creates a new instance with the provided directories added to the path
     * @param array $directories
     * @return AbstractPath
     */
    public function addDirs(array $directories) {
        $existing = $this->dirs;
        $merged = array_merge($existing, $directories);
        $copy = $this->getCopy();
        $copy->dirs = $merged;
        return $copy;
    }
    
    /**
     * Creates a new instance with the provided file
     * @param string $filename
     * @return AbstractPath
     */
    public function file($filename) {
        $copy = $this->getCopy();
        $copy->file = $filename;
        return $copy;
    }
    
    /**
     * Creates a new instance with the provided relative path appended to this
     * @param q\RelativePath $path
     * @return q\AbstractPath
     */
    public function appendRelativePath(RelativePath $path) {
        $copy = $this->getCopy();
        $dirs = $path->getDirs();
        $i = 0;
        while (isset($dirs[$i]) && ($dirs[$i] == '.' || $dirs[$i] == '..')) {
            if ($dirs[$i] == '..') {
                $copy = $copy->parentDir();
            }
            array_shift($dirs);
        }
        if ($dirs != null) {
            $copy = $copy->addDirs($dirs);
        }
        $copy = $copy->file($path->getFile());
        return $copy;
    }
    
    public abstract function toString();
    public abstract function __toString();
    
    public function render() {
        return $this->toString();
    }
}
