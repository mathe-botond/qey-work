<?php
namespace qeywork;

/**
 * Description of TemplatedLayout
 *
 * @author Dexx
 */
abstract class AbstractTemplatedLayout extends AbstractTemplatedBlock implements ILayout {
    /** @var QeyMeta */
    protected $meta;
    protected $content;
    
    public function __construct(QeyMeta $meta) {
        $this->meta = $meta;
    }
    
    public function getContent() {
        return $this->content;
    }
    
    public function getMeta() {
        return $this->meta;
    }

    protected abstract function getAppName();

    private function setTitle() {
        $title = $this->getAppName();
        if (($subTitle = $this->content->getTitle()) != null) {
            $title .= ' - ' . $subTitle;
        }

        $this->meta->setTitle($title);
    }
    
    public function setContent(IPage $content) {
        $this->content = $content;
        $this->add('content', $content);
        $this->setTitle();
    }
    
    public function render(HtmlBuilder $h) {
        $this->add(QeyMeta::NAME, $this->meta);
        return parent::render($h);
    }
}
