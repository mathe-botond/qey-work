<?php
namespace qeywork;

/**
 * List of css links used in HTML header
 *
 * @author Dexx
 */
class AsyncCssLinkLoader extends TemplatedBlock implements ICssLinkCollection
{    
    const TEMPLATE_FILE = 'async-css-loader.js.tpl';
    
    protected $css;
    private $cssLocation;
    
    public function __construct() {
        $this->css = new SimpleRenderebleArray();
    }
    
    public function setCssLocation(Url $cssLocation) {
        $this->cssLocation = $cssLocation;
    }
    
    public function getCssLocation() {
        if ($this->cssLocation == null) {
            throw new \BadMethodCallException('Css Locations is null');
        }
        return $this->cssLocation;
    }

    protected function createEntry($file) {
        return $file . '';
    }    

    public function add() {
        for ($i = 0 ; $i < func_num_args(); $i++) {
            $this->css[] = func_get_arg($i);
        }
    }
    
    protected function provideTemplateFile() {
        $path = new Path(__DIR__);
        return $path->file(self::TEMPLATE_FILE);
    }
}
