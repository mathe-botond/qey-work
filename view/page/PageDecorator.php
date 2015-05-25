<?php
namespace qeywork;
/**
 * @author Dexx
 */
abstract class PageDecorator implements IPage {
    private $page;

    public function setPage(IPage $page) {
        $this->page = $page;
    }
 
    public function getTitle() {
        return $this->page->getTitle();
    }

    public function setType($type) {
        $this->page->setType($type);
    }

    public function isFrontPage() {
        return $this->page->isFrontPage();
    }

    public function isType($type) {
        return $this->page->isType($type);
    }
    
    protected abstract function decorate(IHtmlObject $renderedPage);

    public function render(HtmlBuilder $h) {
        $decoratedPage = $this->decorate($this->page->render($h));
        if (! $decoratedPage instanceof IHtmlObject) {
            throw new ReturnValueException('PageDecorator::decorate', 'IHtmlEntity', $decoratedPage);
        }
        return $decoratedPage;
    }
}
