<?php
namespace qeywork;

abstract class EntityListViewVisualWithActions extends EntityListViewVisual {
    
    /** @var HtmlBuilder */
    private $h;
    
    public function __construct() {
        $this->h = new HtmlBuilder();
    }
    
    protected function getActionsLabel() {
        return 'Actions';
    }
    
    public function header(IHtmlObject $headerCellList) {
        $headerCellList->append(
            $this->headerCell($this->getActionsLabel())
        );
                
        return $this->h->tr()->content(
            $headerCellList
        );
    }
    
    public function headerCell($label) {
        return $this->h->th()->content(
            new TextNode($label)
        );
    }
    
    public function entry($id, IHtmlObject $cells) {
        $cells->append(
            $this->cell($this->actions($id))
        );
                
        return $this->h->tr()->cls($id)->content(
            $cells
        );
    }
    
    public function cell($value) {
        return $this->h->td()->content($value);
    }
    
    protected abstract function actions(Entity $entity);
}
