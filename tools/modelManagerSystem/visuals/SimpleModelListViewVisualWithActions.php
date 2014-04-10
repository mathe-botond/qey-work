<?php
namespace qeywork;

class SimpleModelListViewVisualWithActions extends ModelListViewVisual {
    protected $editLink;
    protected $removeLink;
    
    public function __construct($editLink, $removeLink) {
        $this->editLink = $editLink;
        $this->removeLink = $removeLink;
    }
    
    public function header(IHtmlEntity $headerCellList) {
        $h = new HtmlFactory();
        return  $h->tr()->content(
            $headerCellList,
            $h->th()->text('Actions')
        );
    }
    
    public function entry($id, IHtmlEntity $cells) {
        $h = new HtmlFactory();
        return $h->tr()->content(
            $cells ,
            $h->td()->cls('action')->content(
                $h->a()->cls('action-edit')->href($this->editLink->dir($id))->text('Edit'),
                $h->a()->cls('action-remove')->href($this->removeLink->field('id', $id))->text('Remove')
            )
        );
    }
}
