<?php
namespace qeywork;

class ModelViewVisualUsingTable implements IModelViewVisual{
    protected $h;
    
    public function __construct() {
        $this->h = new HtmlFactory();
    }
    
    public function base($entries) {
        return $this->h->table($entries)->cls('model-view');
    }
    
    public function entry($label, $value) {
        $h = $this->h;
        return $h->tr()->content(
            $h->td()->cls('view-entry-label')->content($label),
            $h->td()->cls('view-entry-value')->content($value)
        );
    }
}
