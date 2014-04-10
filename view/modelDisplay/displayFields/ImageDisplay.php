<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ImageDisplay extends FieldDisplay {
    /** @var Url */
    protected $field;
    protected $alt;
    
    public function __construct(ImageField $field) {
        $this->field = $field;
        parent::__construct($field);
    }
    
    public function setAlt($alt) {
        $this->alt = $alt;
    }
    
    public function render() {
        $url = $this->field->url()->file($this->field->value());
        
        $h = new HtmlFactory();
        $imageNode = $h->img()->cls('thumb')->src($url);
        if ($this->alt != null) {
            $imageNode->alt($this->alt);
        }
        return $imageNode;
    }
}
