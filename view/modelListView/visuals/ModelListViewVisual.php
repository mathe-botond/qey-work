<?php
namespace qeywork;

class ModelListViewVisual implements IModelListViewVisual{
    
    public function base(IHtmlEntity $header, IHtmlEntity $rows) {
        $h = new HtmlFactory();
        return $h->table()->cls('model-list-view')->content(
            $h->thead()->content($header),
            $h->tbody()->content($rows)
        );
    }
    
    public function header(IHtmlEntity $headerCellList) {
        return new HtmlNode('tr', $headerCellList);
    }
    
    public function headerCell($label) {
        $h = new HtmlFactory();
        return $h->th()->content($label);
    }
    
    public function entry($id, IHtmlEntity $cells) {
        $h = new HtmlFactory();
        return $h->tr()->id($id)->content($cells);
    }
    
    public function cell($value) {
        $h = new HtmlFactory();
        return $h->td()->content($value);
    }
}
?>
