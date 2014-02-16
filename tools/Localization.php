<?php
namespace qeywork;

class Localization extends Cacheble
{
    protected $language = null;
    protected $available = null;
    protected $path = null;
    protected $data = null;
    protected $mustReload;
    protected $enabled = false;
    
    public function __construct()
    {
        global $config;
		
        $this->enabled = isset($config['locale']['enabled']) ? $config['locale']['enabled'] : false;
        if (!$this->enabled) return;
        
        $langTokens = explode(',', $config['locale']['all']);
        foreach ($langTokens as $token) {
            $token = trim($token);
            $this->available[] = $token;
        }
        $this->language = $config['locale']['default'];
        $this->path = $config['locale']['path'];
        $this->mustReload = false;
        $this->load();
        $this->enabled = isset($config['locale']['enabled']) ? $config['locale']['enabled'] : false;
    }
    
    public function getCurrent()
    {
        return $this->language;
    }
    
    public function setLanguage($lang)
    {
        if (!$this->enabled) return;
		
        if (array_search($lang, $this->available) === false) {
            throw new LocalizationException("'$this->language' is not listed within the available languages");
        }
        
        $this->language = $lang;
        $this->load();
    }
    
    public function nextLanguage()
    {
        if (!$this->enabled) return;

 		if ($this->language == end($this->available))
            return $this->available[0];

        for ($i = 0; $i < count($this->available)-1; ++$i)
            if ($this->available[$i] == $this->language)
                return $this->available[$i + 1];
    }
    
    public function changeToNextLanguage()
    {
        if (!$this->enabled) return;

        $this->language = $this->nextLanguage();
        $this->load();
        $hist = History::loadInstance();
        $hist->refresh();
    }
    
    public function load()
    {
        if (!$this->enabled) return;

        $str = file_get_contents($this->path->file($this->language . '.json'));
        $this->data = json_decode($str, true);
        if ($this->data == null) {
            throw new LocalizationException("Localization file error: json_decode failed: ".getJsonLastErrorString());
        }
    }
    
    /**
     * @param string $string The token that should be translated
     * @return string the translated string
     * @throws LocalizationException 
     */
    public function translate($string)
    {        
        if (!$this->enabled) return $string;

        $offset = 0;
        while (($posb = strpos($string, '{', $offset)) !== false 
                && ($pose = strpos($string, '}', $posb)) !== false)
        {
            $offset = $posb + 1;
            
            $tplPiece = substr($string,  $posb + 1, $pose - $posb - 1);
            $tplParams = explode('|', $tplPiece);
            
            if (in_array('loc', $tplParams)) {
                $name = end($tplParams);
                $result = $this->get($name);
                $string = substr_replace($string, $result, $posb, $pose - $posb + 1);
            }
        }
        return $string;
    }
    
    public function get($name)
    {
        if (!$this->enabled) return $name;

        if ($this->mustReload) {
            $this->load();
            $this->mustReload = false;
        }
        
        if (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            $this->mustReload = true;
            throw new LocalizationException("Couldn't translate $name into language ".$this->language);
        }
    }
}
?>