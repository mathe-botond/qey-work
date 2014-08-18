<?php
namespace qeywork;

/**
 * @property-read string $name File name
 * @property-read string $type File extension
 * @property-read int $size File size in bytes
 * @property-read string $tmp_name File temporary name
 */
class FileGlobalsHandler
{
    private $file = null;
    protected $valid = false;
    
    public function __construct($fileFieldName) {
        $this->setFile($fileFieldName);
    }
    
    public function setFile($file)
    {
        $this->file = $file;
        if (isset($_FILES[$file])) {
            $this->file = $file;
            $this->valid = $file != null && $_FILES[$file]['error'] == UPLOAD_ERR_OK;
            return $this->valid;
        } else {
            throw new UploadExceptions("File variable '$file' does not exist");
        }
    }
    
    public function isValid() {
        return $this->valid;
    }
    
    //POSSIBLE KEYS: name, type, size, tmp_name
    public function __get($key)
    {
        if (isset($_FILES[$this->file][$key])) {
            return $_FILES[$this->file][$key];
        } else {
            throw new UploadExceptions("File variable '$key' does not exist");
        }
    }
    
    public function getSize() {
        if ($this->valid) {
            return $this->size / 1024;
        }
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function exists($key)
    {
        return isset($_SESSION[$this->file][$key]);
    }
    
    public function save($path)
    {
        $result = move_uploaded_file($_FILES[$this->file]["tmp_name"], $path);
        if (! $result) {
            throw new UploadExceptions('Something went wrong');
        }
    }
    
    public function getUniqueName($dir, $filename, $ext)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        while (file_exists($dir . $filename . '.' . $ext)) {
            $filename .= $chars[rand(0, 35)];
        }
        return $filename;
    }
    
    public function getError() {
        if (isset($_FILES[$this->file]['error'])) {
            $errorCode = $_FILES[$this->file]['error'];
            switch ($errorCode) {
                case UPLOAD_ERR_CANT_WRITE:
                    return 'Failed to write file to disk.';
                case UPLOAD_ERR_EXTENSION:
                    return 'An extension stopped the file upload. PHP does not provide a way to '.
                    'ascertain which extension caused the file upload to stop; '.
                    'examining the list of loaded extensions with phpinfo() may help.';
                case UPLOAD_ERR_FORM_SIZE:
                    return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
                case UPLOAD_ERR_INI_SIZE:
                    return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                case UPLOAD_ERR_NO_FILE:
                    return 'No file was uploaded.';
                case UPLOAD_ERR_NO_TMP_DIR:
                    return 'Missing a temporary folder.';
                case UPLOAD_ERR_PARTIAL:
                    return 'The uploaded file was only partially uploaded.';
            }
        }
        return 'unknown error';
    }

}