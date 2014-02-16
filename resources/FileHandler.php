<?php
namespace qeywork;

class FileHandler extends Singleton
{
    private $file = "file";
    public $valid = false;
    
    function __construct($file = "file")
    {
        $this->setFile($file);
    }
    
    public function setFile($file)
    {
        if (isset($_FILES[$file])) 
            return $this->valid = ($this->file = $file) != null && !$_FILES[$file]['error'];
        else
        {
            throw new UploadExceptions("File variable ".$file." does not exist");
            return $this->valid = false;
        }
    }
    
    //POSSIBLE KEYS: name, type, size, tmp_name
    function __get($key = null)
    {
        if(isset($_FILES[$this->file][$key]))
            return $_FILES[$this->file][$key];
        else
        {
            throw new UploadExceptions("File variable ".$key." does not exist");
            return false;
        }
    }
    
    function exists($key)
    {
        return isset($_SESSION[$this->file][$key]);
    }
    
    function save($path)
    {
        move_uploaded_file($_FILES[$this->file]["tmp_name"], $path);
    }
    
    function getUniqueName($dir, $filename, $ext)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        while (file_exists($dir . $filename . '.' . $ext)) {
            $filename .= $chars[rand(0, 35)];
        }
        return $filename;
    }
}
?>