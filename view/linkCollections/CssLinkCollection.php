<?php
namespace qeywork;

/**
 * List of css links used in HTML header
 *
 * @author Dexx
 */
class CssLinkCollection extends LinkCollection
{
    /** @var Url */
    protected $cssLocation;
    
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
    
    protected function createEntry($file) {
        $h = new HtmlFactory();
        return $h->link()->rel('stylesheet')->href($file)->type('text/css')->media('all');
    }
}
?>
