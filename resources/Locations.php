<?php
namespace qeywork;

class Locations {
    private $pendingRedirect = false;
    
    /** @var Url */
    public $baseUrl;
    
    /** @var Url */
    public $homePage;
    /** @var Url */
    public $appJs;
    /** @var Url */
    public $qeyWorkJs;
    /** @var Url */
    public $css;
    /** @var Url */
    public $cssImages;
    /** @var Url */
    public $filesUrl;
    
    /** @var Path */
    public $basePath;
    /** @var Path */
    public $app;
    /** @var Path */
    public $qeyWork;
    /** @var Url */
    public $filesPath;
    
    public function __construct(Url $baseUrl, Path $basePath) {
        $this->baseUrl = $baseUrl;
        $this->homePage = $baseUrl;
        $this->basePath = $basePath;
    }
    
    public function addTokenDictionary(ITokenDictionary $dictionary) {
        $this->tokenDictionaries = array_unshift($this->tokenDictionaries, $dictionary);
    }

    /**
     * @return Url to action
     */
    public function getUrlOfAction($name, $arguments = array()) {
        return $this->baseUrl->param('q')->param($name)->addParams($arguments);
    }

    /**
     * @return Url to page
     */
    public function getUrlOfPage($name, $arguments = array()) {
        return $this->baseUrl->param($name)->addParams($arguments);
    }
    
    public function redirect($target, $dieOnHit = false) {
        if ($this->pendingRedirect) {
            throw new ResourceException('Pending redirect');
        }
        
        if (strpos($target, "\n") !== false || strpos($target, "\r") !== false) {
            throw new ResourceException("Redirect faled: Target contains new lines: '$target'");
        }

        header("Location: " . $target);
        
        $this->pendingRedirect = true;
        
        if ($dieOnHit) {
            die();
        }
    }
}

?>
