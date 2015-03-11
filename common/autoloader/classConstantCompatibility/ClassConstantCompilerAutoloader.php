<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ClassConstantCompilerAutoloader extends Autoloader {

    const CLASS_LIST = 'class-list';
    const PREPROCESSED = 'preprocessed';

    private $preprocessed;
    private $classConstantCompiler;

    protected function isPreprocessedNeeded() {
        //return PHP_VERSION_ID < 50500;
        //TODO: Fix this
        return true;
    }
    
    public function __construct($path, $key,
            $compilerOutput = ClassConstantCompatibilityCompiler::DEFAULT_OUTPUT) {
        if ($this->isPreprocessedNeeded()) {
            $compilerPath = $path . DIRECTORY_SEPARATOR . $compilerOutput;
            $this->classConstantCompiler
                    = new ClassConstantCompatibilityCompiler($compilerPath);
        }
        parent::__construct($path, $key);
    }
    
    protected function initProcessor() {
        foreach ($this->preprocessed as $entry) {
            $dir = $this->path . DIRECTORY_SEPARATOR . $entry['path'];
            $this->classConstantCompiler->addFile($dir, $entry['file']);
        }
        $this->classConstantCompiler->compile();
    }
    
    public function init() {
        if (! $this->isPreprocessedNeeded()) {
            parent::init();
            return;
        }

        if (file_exists($this->file)) {
            $content = file_get_contents($this->file);
            $content = unserialize($content);
            $this->classList = $content[self::CLASS_LIST];
            $this->preprocessed = $content[self::PREPROCESSED];
        } else {
            $this->gatherClassFiles();
            $content = array(
                self::CLASS_LIST => $this->classList,
                self::PREPROCESSED => $this->preprocessed
            );
            file_put_contents($this->file, serialize($content));
        }
        $this->initProcessor();
    }

    protected function gatherClassFiles() {
        parent::gatherClassFiles();
        if ($this->isPreprocessedNeeded()) {
            foreach ($this->classList as $fileData) {
                if (substr($fileData['file'], -9) === '.prec.php') {
                    $preprocessedEntry = array(
                        'path' => implode(DIRECTORY_SEPARATOR, $fileData['path']),
                        'file' => $fileData['file']
                    );
                    $this->preprocessed[] = $preprocessedEntry;
                }
            }
        }
    }
}
