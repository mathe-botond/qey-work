<?php
namespace QeyWork\View\LinkCollections;
use QeyWork\Common\Addresses\Locations;
use QeyWork\Common\Addresses\Url;
use QeyWork\View\Html\HtmlBuilder;

/**
 * List of css links used in HTML header
 *
 * @author Dexx
 */
class CssLinkCollection extends LinkCollection implements ICssLinkCollection
{
    /** @var Url */
    protected $cssLocation;
    
    public function __construct(Locations $loc) {
        $this->cssLocation = $loc->css;
        
        parent::__construct();
    }
    
    public function setCssLocation(Url $cssLocation) {
        $this->cssLocation = $cssLocation;
    }
    
    /**
     * @return Url
     * @throws \BadMethodCallException
     */
    public function getCssLocation() {
        if ($this->cssLocation == null) {
            throw new \BadMethodCallException('Css Locations is null');
        }
        return $this->cssLocation;
    }
    
    protected function createEntry(HtmlBuilder $h, $file) {
        return $h->link()->rel('stylesheet')->href($file)->type('text/css')->media('all');
    }
}
