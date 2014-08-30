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
    
    public function __construct(QeyMeta $meta) {
        $this->meta = $meta;
        $this->add(QeyMeta::NAME, $meta);
    }
    
    public function getContent() {
        return $this->content;
    }
    
    public function getMeta() {
        return $this->meta;
    }
    
    protected function setTitle() {
        $this->meta->setTitle($this->content->getTitle());
    }
    
    public function setContent(IPage $content) {
        $this->content = $content;
        $this->add('content', $content);
        $this->setTitle();
    }
}
