<?php
namespace qeywork;

class History
{
    private $history;
    private $pointer;
    private $homePage;
    private $server;

    public function __construct(Url $home, SuperGlobals $globals)
    {
        $this->homePage = $home;
        $this->history = array();
        $this->pointer = 0;
        $this->server = $globals->getServer();
    }
    
    //TODO: dont insert refresh
    public function addCurrent()
    {
        if (!empty($this->server['HTTPS'])) {
            $url = "https://" . $this->server['SERVER_NAME'] . $this->server['REQUEST_URI'];
        } else {
            $url = "http://" . $this->server['SERVER_NAME'] . $this->server['REQUEST_URI'];
        }

        //If page was refreshed do not add to history
        if ($this->pointer > 0 && $url === $this->history[$this->pointer - 1]) {
            return;
        }

        $this->history[$this->pointer] = $url;
        ++$this->pointer;
    }
    
    public function back($step = 1)
    {
        if ($this->pointer <= $step) {
            redirect($this->homePage);
        } else {
            $this->pointer -= $step;
            redirect($this->history[$this->pointer - 1]);
        }
    }
    
    public function forward($step = 1)
    {
        if ($this->pointer >= count($this->history) + $step) {
            redirect($this->homePage);
        } else {
            $this->pointer += $this->step;
            redirect($this->history[$this->pointer - 1]);
        }
    }
    
    public function refresh()
    {
        if ($this->pointer == 0)
            redirect($this->homePage);
        else
            redirect($this->history[$this->pointer - 1]);
    }
}
