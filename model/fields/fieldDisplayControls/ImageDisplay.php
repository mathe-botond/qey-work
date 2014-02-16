<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ImageDisplay extends FieldDisplay {
    /** @var Url */
    private $base;
    protected $alt;
    
    public function __construct(Url $base) {
        $this->base = $base;
    }
    
    public function setAlt($alt) {
        $this->alt = $alt;
    }
    
    public function render() {
        $image = $this->base->file($this->value);
        $h = new HtmlFactory();
        $imageNode = $h->img()->src($image);
        if ($this->alt != null) {
            $imageNode->alt($this->alt);
        }
        return $imageNode;
    }
}

?>
