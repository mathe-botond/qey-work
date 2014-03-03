<?php
namespace qeywork;

class ImageField extends Field {
    protected $url;
    
    public function url() {
        return $this->url;
    }
    
    public function setUrl(Url $url) {
        $this->url = $url;
    }
}
?>
