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
    
    public function render(HtmlBuilder $h) {
        $url = $this->field->url()->file($this->field->value());
        

        $imageNode = $h->img()->cls('thumb')->src($url);
        if ($this->alt != null) {
            $imageNode->alt($this->alt);
        }
        return $imageNode;
    }
}
