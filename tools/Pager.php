<?php
namespace qeywork;

/**
 * @author Dexx
 */
class Pager implements IRenderable {

    /** @var q\Url */
    private $url;
    
    private $pageCount;
    private $currentPage;

    public function __construct(Url $baseUrl, $pageCount, $currentPage) {        
        $this->currentPage = $currentPage;
        $this->pageCount = $pageCount;
        $this->url = $baseUrl;
    }
    
    function render() {
        $numberOfPages = ceil($this->pageCount);
        $prevPageNumber = $this->currentPage - 1;
        $nextPageNumber = $this->currentPage + 1;
        $h = new HtmlFactory();

        $paginator = $h->nav()->cls('pagination');
        if ($this->currentPage > 1) {
            $paginator->append(
                $h->a()->cls('pagination-item')
                    ->href($this->url->param($prevPageNumber))
                    ->htmlContent('&lt;')
            );
        }

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
                $paginator->append($pages[$i]);
                if ($i < $numberOfPages - 1 && !isset($pages[$i + 1])) {
                    $paginator->append(new TextNode("..."));
                }
            }
        }

        if ($this->currentPage < $numberOfPages) {
            $paginator->append(
                $h->a()->cls('pagination-item')
                    ->href($this->url->param($nextPageNumber))
                    ->htmlContent('&gt;')
            );
        }

        return $paginator;
    }
}
