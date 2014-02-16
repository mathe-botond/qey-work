<?php
namespace qeywork;

class Upload extends Singleton
{
    private $maximumFileSize;
    private $uploadDir;
    private $allowedExtensions;
    
    public function __construct($uploadDir, $maxFileSize = null, $allowedExtensions = null)
    {        
        $this->uploadDir = $uploadDir;
        $this->maximumFileSize = ($maxFileSize !== null) ? $maxFileSize : null;
        $this->allowedExtensions = ($allowedExtensions !== null) ? $allowedExtensions : null;
        
        if (! is_dir($this->uploadDir)) {
            throw new ArgumentException('Upload dir was not found');
        }
    }
    
    public function upload($fileInput)
    {        
        if (empty($fileInput)) {
            throw new ArgumentException('No file input name specified');
        }
        
        $f = new FileHandler($fileInput);
        if (!$f->valid) return '';
        $size = $f->size;
        
        if ($size == 0){
            throw new UploadExceptions('File is empty', 1);
        } 
        if ($this->maximumFileSize != null && $size > $this->maximumFileSize){
            throw new UploadExceptions('File too large', 2);
        }
        
        $pathinfo = pathinfo($f->name);
        $name = $pathinfo['filename'];            
        $extension = $pathinfo['extension'];
        
        //limit file extensions on server side
        if ($this->allowedExtensions !== null) {
            $extExists = false;
            foreach ($this->allowedExtensions as $ext) {
                if (strtolower($extension) == $ext) {
                    $extExists = true;
                    break;
                }
            }
            
            if (! $extExists) {
                throw new UploadExceptions('File extension mismatch', 4);
            }
        }
        
        $name = $f->getUniqueName($this->uploadDir, $name, $extension);
        $f->save($this->uploadDir . $name . '.' . $extension);

        return $name . '.' . $extension;
    }
}
?>
