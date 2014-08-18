<?php
namespace qeywork;

class Uploader
{
    private $filesGlobalHandler;
    
    protected $valid;
    
    public function __construct(FileGlobalsHandler $filesGlobalHandler)
    {        
        $this->filesGlobalHandler = $filesGlobalHandler;
        $this->valid = false;
    }
    
    /**
     * Validate file upload
     * @param array $allowedExtensions
     * @param int $maxFileSize in kB
     * @return string|boolean
     */
    public function validate(array $allowedExtensions = null, $maxFileSize = null) {
        if (! $this->filesGlobalHandler->isValid() ) {
            throw new UploadExceptions('File upload failed: ' . $this->filesGlobalHandler->getError());
        }
        
        $size = $this->filesGlobalHandler->getSize();
        if ($size == 0){
            throw new UploadExceptions('File is empty');
        }
        
        if ($maxFileSize != null && $size > $maxFileSize){
            throw new UploadExceptions('File too large');
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
                throw new UploadExceptions('File extension not allowed');
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
