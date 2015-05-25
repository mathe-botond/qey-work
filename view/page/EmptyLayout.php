<?php
namespace qeywork;

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

    public function render() {
        $h = new HtmlFactory();
        
        $layout = $h->html()->content(
            $h->head()->content(
                $this->meta->render()
            ),
            $h->body()->content(
                $this->content->render()
            )
        );
        
        return $layout;
    }
}
