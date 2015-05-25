<?php
namespace qeywork;

class EntityViewVisualUsingTable implements IEntityViewVisual{
    protected $h;
    
    public function __construct() {
        $this->h = new HtmlBuilder();
    }
    
    public function base($entries) {
        return $this->h->table($entries)->cls('entity-view');
    }
    
    public function entry($label, $value) {
        $h = $this->h;
        return $h->tr()->content(
            $h->td()->cls('view-entry-label')->content($label),
            $h->td()->cls('view-entry-value')->content($value)
        );
    }
}
