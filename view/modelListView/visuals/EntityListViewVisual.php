<?php
namespace qeywork;

class EntityListViewVisual implements IEntityListViewVisual{

    /** @var HtmlBuilder */
    private $h;

    public function __construct(HtmlBuilder $h) {
        $this->h = $h;
    }

    public function base(IHtmlObject $header, IHtmlObject $rows) {
        return $this->h->table()->cls('entity-list-view')->content(
            $this->h->thead()->content($header),
            $this->h->tbody()->content($rows)
        );
    }
    
    public function header(IHtmlObject $headerCellList) {
        return $this->h->tr()->content($headerCellList);
    }
    
    public function headerCell($label) {
        return $this->h->th()->htmlContent($label);
    }
    
    public function entry($id, IHtmlObject $cells) {

        return $this->h->tr()->id($id)->content($cells);
    }
    
    public function cell($value) {

        return $this->h->td()->htmlContent($value);
    }
}
