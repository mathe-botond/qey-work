<?php
namespace QeyWork\View\Page;
use QeyWork\View\Block\QeyMeta;
use QeyWork\View\Html\HtmlBuilder;

/**
 * @author Dexx
 */
class EmptyLayout implements ILayout {

    /**
     * @var QeyMeta
     */
    private $meta;
    private $content;
    
    public function __construct(QeyMeta $meta) {
        $this->meta = $meta;
    }
    
    public function getMeta() {
        return $this->meta;
    }

    public function setContent(IPage $content) {
        $this->content = $content;
    }

    public function render(HtmlBuilder $h) {

        
        $layout = $h->html()->content(
            $h->head()->content(
                $this->meta->render($h)
            ),
            $h->body()->content(
                $this->content->render($h)
            )
        );
        
        return $layout;
    }
}
