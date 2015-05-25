<?php
namespace qeywork;
interface ICodeProvider {
    public function getLine();
    public function reset();
}

class StringCodeProvider implements ICodeProvider {
    private $lines;
    private $pointer = 0;
    
    public function __construct($string) {
        $this->lines = explode("\n", $string);
    }
    
    public function getLine() {
        if (isset($this->lines[$this->pointer])) {
            return trim($this->lines[$this->pointer++]);
        }
        return false;
    }
    
    public function reset() {
        $this->pointer = 0;
    }
}

class PhpFileCodeProvider implements ICodeProvider {
    /** @var \SplFileObject */
    private $f;

    public function __construct(\SplFileInfo $file) {        
        $this->f = $file->openFile();
    }
    
    public function getLine() {
        if (! $this->f->eof()) {
            return $this->f->fgets();
        } else {
            return false;
        }
    }
    
    public function reset() {
        $this->f->rewind();
    }
}

class PhpNamespaceParser {
    private $inPhp = false;
    
    private function inPhp($line) {
        if (! $this->inPhp) {
            if (($pos = strpos($line, '<?php')) !== false) {
                $this->inPhp = true;
                //remove <?php and everything before it from this line
                //because the namespace clause might be in this exact same line
                return substr($line, $pos + 5);
            }
        } else {
            return $line;
        }
    }
    
    public function extractNamespace(ICodeProvider $code) {
        $this->inPhp = false;
        $matches = array();
        while (($line = $code->getLine()) !== false) {
            if (($line = $this->inPhp($line)) != null) {
                if (preg_match('/\\s*namespace\\s+([\\w\\d\\\\]+)\\s*;/', $line, $matches)) {
                    $namespace = $matches[1];
                    return $namespace;
                }
            }
        }
    }
    
    public function extractUses(ICodeProvider $code) {
        $this->inPhp = false;
        $matches = array();
        $uses = array();
        while (($line = $code->getLine()) !== false) {
            if (($line = $this->inPhp($line)) != null) {
                if (preg_match('/\\s*use\\s+([\\w\\d\\\\]+)\\s+as\\s+([\\w\\d\\\\]+)\\s*;/', $line, $matches)) {
                    $uses[$matches[2]] = $matches[1];
                }
            }
        }
        return $uses;
    }
}

