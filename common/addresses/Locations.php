<?php
namespace QeyWork\Common\Addresses;

use QeyWork\Common\ResourceException;

class Locations {
    const DEFAULT_LOG_FILE = 'log.txt';
    const DEFAULT_STYLE_DIR = 'style';
    const DEFAULT_IMAGES_DIR = 'images';
    const DEFAULT_JS_DIR = 'js';
    const DEFAULT_FILES_DIR = 'files';
    const LAYOUT_DIR = 'layout';
    
    private $pendingRedirect = false;
    
    /** @var Url */
    public $baseUrl;
    
    /** @var Url */
    public $homePage;
    /** @var Url */
    public $appUrl;
    /** @var Url */
    public $appJs;
    /** @var Url */
    private $qeyWorkUrl;
    /** @var Url */
    public $qeyWorkJs;
    /** @var Url */
    public $css;
    /** @var Url */
    public $cssImages;
    /** @var Url */
    public $filesUrl;
    /** @var Url */
    public $filesPath;
    
    /** @var Path */
    public $basePath;
    /** @var Path */
    public $appPath;
    /** @var Path */
    public $qeyWorkPath;
    /** @var Path */
    public $logFilePath;

    public function __construct(
            Location $base,
            RelativePath $app,
            RelativePath $qeyWork) {
        
        $this->baseUrl = $base->getRemote();
        $this->homePage = $this->baseUrl;
        $this->basePath = $base->getLocal();
        
        $this->appPath = $this->basePath->appendRelativePath($app);
        $this->appUrl = $this->baseUrl->appendRelativePath($app);
        
        $layout = $this->appUrl->dir(self::LAYOUT_DIR);
        $this->css = $layout->dir(self::DEFAULT_STYLE_DIR);
        $this->cssImages = $this->css->dir(self::DEFAULT_IMAGES_DIR);
        
        $this->appJs = $this->appUrl->dir(self::DEFAULT_JS_DIR);
        
        $this->qeyWorkPath = $base->getLocal()->appendRelativePath($qeyWork);
        $this->qeyWorkUrl = $base->getRemote()->appendRelativePath($qeyWork);
        $this->qeyWorkJs = $this->qeyWorkUrl->dir(self::DEFAULT_JS_DIR);
        
        $this->filesUrl = $this->baseUrl->dir(self::DEFAULT_FILES_DIR);
        $this->filesPath = $this->basePath->dir(self::DEFAULT_FILES_DIR);
        
        $this->logFilePath = $this->appPath->file(self::DEFAULT_LOG_FILE);
    }
    
    //public function addTokenDictionary(ITokenDictionary $dictionary) {
    //    $this->tokenDictionaries = array_unshift($this->tokenDictionaries, $dictionary);
    //}

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
