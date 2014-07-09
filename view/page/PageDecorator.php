<?php
namespace qeywork;
/**
 * @author Dexx
 */
abstract class PageDecorator implements IPageByToken {
    private $page;

    public function __construct(IPageByToken $page) {
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

    public function getToken() {
        return $this->page->getToken();
    }

    public function setToken($token) {
        $this->page->setToken($token);
    }
    
    protected abstract function decorate(IHtmlEntity $page);

    public function render() {
        $decoratedPage = $this->decorate($this->page->render());
        if (! $decoratedPage instanceof IHtmlEntity) {
            throw new ReturnValueException($decoratedPage, 'IHtmlEntity');
        }
        return $decoratedPage;
    }
}
