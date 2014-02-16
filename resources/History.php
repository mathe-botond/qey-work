<?php
namespace qeywork;

class History
{
    private $history;
    private $pointer;
    
    public function __construct()
    {  
        $this->history = array();
        $this->pointer = 0;
    }
    
    //TODO: dont insert refresh
    public function addCurrent()
    {
        if (!empty($_SERVER['HTTPS']))
            $url = "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        else
            $url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
            
        //If page was refreshed do not add to history
        if ($this->pointer > 0 && $url === $this->history[$this->pointer - 1])
            return;
        
        $this->history[$this->pointer] = $url;
        ++$this->pointer;
    }
    
    public function back($step = 1)
    {
        if ($this->pointer <= $step) {
            Redirect::goHome();
        } else {
            $this->pointer -= $step;
            redirect($this->history[$this->pointer - 1]);
        }
    }
    
    public function forward($step = 1)
    {
        if ($this->pointer >= count($this->history) + $step) {
            Redirect::goHome();
        } else {
            $this->pointer += $this->step;
            redirect($this->history[$this->pointer - 1]);
        }
    }
    
    public function refresh()
    {
        if ($this->pointer == 0)
            Redirect::goHome();
        else
            redirect($this->history[$this->pointer - 1]);
    }
}
?>