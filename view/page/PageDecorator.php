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
    
    protected abstract function decorate(IHtmlEntity $page);

    public function render() {
        $decoratedPage = $this->decorate($this->page->render());
        if (! $decoratedPage instanceof IHtmlEntity) {
            throw new ReturnValueException('PageDecorator::decorate', 'IHtmlEntity', $decoratedPage);
        }
        return $decoratedPage;
    }
}
