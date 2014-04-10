<?php
namespace qeywork;

class FileGlobalsHandler
{
    private $file = null;
    protected $valid = false;
    
    public function setFile($file)
    {
        $this->file = $file;
        if (isset($_FILES[$file])) {
            return $this->valid = ($this->file = $file) != null && !$_FILES[$file]['error'];
        } else {
            throw new UploadExceptions("File variable " . $file . " does not exist");
        }
    }
    
    public function isValid() {
        return $this->valid;
    }
    
    //POSSIBLE KEYS: name, type, size, tmp_name
    public function __get($key = null)
    {
        if (isset($_FILES[$this->file][$key])) {
            return $_FILES[$this->file][$key];
        } else {
            throw new UploadExceptions("File variable " . $key . " does not exist");
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

}