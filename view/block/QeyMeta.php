<?php
namespace qeywork;

class QeyMeta implements IRenderable
{
    protected $title = null;
    /** @var HtmlEntityList */
    protected $meta;
    
    /** @var CssLinkCollection */
    public $cssLinks;
    /** @var JsLinkCollection */
    public $jsLinks;
    
    public function __construct(CssLinkCollection $css, JsLinkCollection $js) {
        $this->cssLinks = $css;
        $this->jsLinks = $js;
        
        $this->meta = new HtmlEntityList();
        $h = new HtmlFactory();

        $this->setHeaderEntry('encoding', $h->meta()
                ->attr('http-equiv', 'Content-Type')
                ->attr('content', 'text/html; charset=UTF8')
            );
        $this->setHeaderEntry('robots', $h->meta()
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
    
    public function setHeaderEntry($key, HtmlNode $node)
    {
        $this->meta[$key] = $node;
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
?>