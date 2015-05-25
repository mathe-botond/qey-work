<?php
namespace qeywork;

/**
 * @author Dexx
 */
class Pager implements IRenderable {

    /** @var Url */
    private $url;
    
    private $pageCount;
    private $currentPage;

    public function __construct(Url $baseUrl, $pageCount, $currentPage) {        
        $this->currentPage = $currentPage;
        $this->pageCount = $pageCount;
        $this->url = $baseUrl;
    }

    public function getCurrentPageNumber() {
        return $this->currentPage;
    }

    /**
     * TODO: Refactor code
     * @param HtmlBuilder $h
     * @return NullHtml
     */
    public function render(HtmlBuilder $h) {
        $numberOfPages = ceil($this->pageCount);

        if ($numberOfPages < 2) {
            return new NullHtml();
        }

        $prevPageNumber = $this->currentPage - 1;
        $nextPageNumber = $this->currentPage + 1;


        $pagination = $h->nav()->cls('pagination');
        if ($this->currentPage > 1) {
            $pagination->append(
                $h->a()->cls('pagination-item')
                    ->href($this->url->param($prevPageNumber))
                    ->htmlContent('&lt;')
            );
        }

        $pages = array();
        for ($i = 1; $i <= $numberOfPages; $i++) {
            if ($i != $this->currentPage) {
                $pages[$i] = $h->a()->cls('pagination-item')
                        ->href($this->url->addParam($i))->text($i);
            } else {
                $pages[$i] = $h->span()->cls('pagination-item inactive')->text($i);
            }
        }

        for ($i = 2; $i <= $this->currentPage - 2; $i++) {
            unset($pages[$i]);
        }
        for ($i = $this->currentPage + 2; $i <= $numberOfPages - 1; $i++) {
            unset($pages[$i]);
        }

        for ($i = 1; $i <= $numberOfPages; $i++) {
            if (isset($pages[$i])) {
                $pagination->append($pages[$i]);
                if ($i < $numberOfPages - 1 && !isset($pages[$i + 1])) {
                    $pagination->append(new TextNode("..."));
                }
            }
        }

        if ($this->currentPage < $numberOfPages) {
            $pagination->append(
                $h->a()->cls('pagination-item')
                    ->href($this->url->param($nextPageNumber))
                    ->htmlContent('&gt;')
            );
        }

        return $pagination;
    }
}
