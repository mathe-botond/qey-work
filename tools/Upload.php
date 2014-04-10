<?php
namespace qeywork;

class Upload
{
    private $filesGlobalHandler;
    
    protected $name;
    protected $valid;
    
    public function __construct(FileGlobalsHandler $filesGlobalHandler)
    {        
        $this->filesGlobalHandler = $filesGlobalHandler;
    }
    
    public function setFile($file) {
        $this->name = $file;
        $this->valid = false;
    }
    
    public function validate(array $allowedExtensions = null, $maxFileSize = null) {        
        $this->filesGlobalHandler->setFile($this->name);
        if (! $this->filesGlobalHandler->isValid() ) {
            return 'File is not in the FILES global';
        }
        
        $size = $this->filesGlobalHandler->getSize();
        if ($size == 0){
            return 'File is empty';
        }
        
        if ($maxFileSize != null && $size > $maxFileSize){
            return 'File too large';
        }
        
        $pathinfo = pathinfo( $this->filesGlobalHandler->getName() );
        $extension = strtolower($pathinfo['extension']);
        
        if ($allowedExtensions !== null) {
            $extExists = false;
            foreach ($allowedExtensions as $ext) {
                if (strtolower($extension) == $ext) {
                    $extExists = true;
                    break;
                }
            }
            
            if (! $extExists) {
                return 'File extension not allowed';
            }
        }
        
        $this->valid = true;
        return true;
    }


    public function upload(Path $uploadDir)
    {
        if (! $this->valid) {
            throw new \BadMethodCallException('Validate file first');
        }
        
        if (! is_dir($uploadDir)) {
            throw new ArgumentException('Upload dir was not found');
        }

        $pathinfo = pathinfo($this->filesGlobalHandler->getName());
        $name = $pathinfo['filename'];            
        $extension = $pathinfo['extension'];
        
        //limit file extensions on server side        
        $localName = $this->filesGlobalHandler->getUniqueName($uploadDir, $name, $extension);
        
        $this->filesGlobalHandler->save(
                $uploadDir . $localName . '.' . $extension);

        return $localName . '.' . $extension;
    }
}
