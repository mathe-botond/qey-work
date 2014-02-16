<?php
namespace qeywork;

class ModelListViewVisual implements IModelListViewVisual{
    const ACTION_EDIT = 2;
    const ACTION_DELETE = 4;
    
    protected $actions;
    
    public function __construct($actions = 6) {
        $this->actions = $actions;
    }
    
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
    
    public function actions() {
        $h = new HtmlFactory();
        if ($this->actions !== 0) {
            $container = $h->td();

            if ($this->actions & self::ACTION_EDIT) {
                $container->append(
                    $h->a()->cls('action')->dataAction('edit')
                );
            }

            if ($this->actions & self::ACTION_DELETE) {
                $container->append(
                    $h->a()->cls('action')->dataAction('delete')
                );
            }

            return $h->tr($container);
        }
        return null;
    }
}
?>
