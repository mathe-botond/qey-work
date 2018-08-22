<?php
namespace QeyWork\View\ModelListView\Visuals;

use QeyWork\Entities\Entity;
use QeyWork\View\Html\HtmlBuilder;
use QeyWork\View\Html\IHtmlObject;
use QeyWork\View\Html\TextNode;

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
