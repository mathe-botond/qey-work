<?php
namespace qeywork;

class QeyMeta implements IRenderable
{
    protected $title = null;
    /** @var HtmlEntityList */
    protected $meta;
    
    /** @var ILinkCollection */
    public $cssLinks;
    /** @var ILinkCollection */
    public $jsLinks;
    
    public function __construct(ICssLinkCollection $css, JsLinkCollection $js) {
        $this->cssLinks = $css;
        $this->jsLinks = $js;
        
        $this->meta = new HtmlEntityList();
        $h = new HtmlFactory();

        $this->addHeaderEntry('encoding', $h->meta()
                ->attr('http-equiv', 'Content-Type')
                ->attr('content', 'text/html; charset=utf-8')
            );
        $this->addHeaderEntry('robots', $h->meta()
                ->attr('name', 'robots')
                ->attr('content', 'all'));
        //$this->setHeaderEntry('favicon', $h->meta()
        //        ->attr('rel', 'icon')
        //        ->attr('type', 'image/png')
        //        ->href('{path}view/style/images/favicon.png'));
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function addHeaderEntry($key, HtmlNode $node)
    {
        $this->meta[$key] = $node;
    }
    
    public function addMetaEntry($name, $content) {
        $node = new HtmlNode('meta', true);
        $node->attr('name', $name)->attr('content', $content);
        $this->addHeaderEntry($name, $node);
    }
    
    /**
     * @return CssLinkCollection
     */
    public function getCssLinkCollection() {
        return $this->cssLinks;
    }
    
    /**
     * @return JsLinkCollection
     */
    public function getJsLinkCollection() {
        return $this->jsLinks;
    }
    
    public function render()
    {
        $h = new HtmlFactory();
        $css = $this->cssLinks->render();
        $js = $this->jsLinks->render();
        
        $headerStrings = $this->meta;
        $headerStrings->append($h->title()->content($this->title));
        $headerStrings->append($css);
        $headerStrings->append($js);
        return $headerStrings;
    }
}
