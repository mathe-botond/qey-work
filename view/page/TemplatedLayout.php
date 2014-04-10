<?php
namespace qeywork;

/**
 * Description of TemplatedLayout
 *
 * @author Dexx
 */
abstract class TemplatedLayout extends TemplatedBlock implements ILayout {
    /** @var QeyMeta */
    protected $meta;
    protected $content;
    
    public function __construct(Locations $locations) {
        $this->meta = $this->createMeta($locations);
    }
    
    public function getContent() {
        return $this->content;
    }
    
    protected function createMeta(Locations $locations) {
        $css = new CssLinkCollection();
        $css->setCssLocation($locations->css);
        
        $js = new JsLinkCollection();
        $js->setAppJs($locations->appJs);
        $js->setQeyWorkJs($locations->qeyWorkJs);
        
        return new QeyMeta($css, $js);
    }
    
    public function getMeta() {
        return $this->meta;
    }
    
    protected function setTitle() {
        $this->meta->setTitle($this->content->getTitle());
    }
    
    public function setContent(IPage $content) {
        $this->content = $content;
        $this->setTitle();
    }
}
