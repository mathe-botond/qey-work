<?php
namespace QeyWork\View\Page;
use QeyWork\View\Block\AbstractTemplatedBlock;
use QeyWork\View\Block\QeyMeta;
use QeyWork\View\Html\HtmlBuilder;


/**
 * Description of TemplatedLayout
 *
 * @author Dexx
 */
abstract class AbstractTemplatedLayout extends AbstractTemplatedBlock implements ILayout {
    /** @var QeyMeta */
    protected $meta;
    /** @var IPage */
    protected $content;
    /** @var string */
    private $title;

    public function __construct($title, QeyMeta $meta) {
        $this->meta = $meta;
        $this->title = $title;
    }
    
    public function getContent() {
        return $this->content;
    }
    
    public function getMeta() {
        return $this->meta;
    }

    private function setTitle() {
        $title = $this->title;
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
