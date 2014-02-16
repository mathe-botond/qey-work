<?php
namespace qeywork;

class ModelListViewVisualWithActions extends ModelListViewVisual {
    const ACTION_EDIT = 2;
    const ACTION_DELETE = 4;
    
    protected $actions;
    
    public function __construct($actions = 6) {
        $this->actions = $actions;
    }
    
    public function header($headerCellList) {
        $h = new HtmlFactory();
        echo qeyNode('tr', $headerCellList);
    }
    
    public function headerCell($label) {
        echo qeyNode('th', $label);
    }
    
    public function entry($id, $cells) {
        echo qeyNode('tr', $cells)->id($id);
    }
    
    public function cell($value) {
        echo qeyNode('td', $value);
    }
    
    public function actions() {
        if ($this->actions !== 0) {
            $container = qeyNode('td');

            if ($this->actions & self::ACTION_EDIT) {
                $container->append(
                    qeyNode('a')->cls('action')->dataAction('edit')
                );
            }

            if ($this->actions & self::ACTION_DELETE) {
                $container->append(
                    qeyNode('a')->cls('action')->dataAction('delete')
                );
            }

            echo qeyNode('tr')->html($container);
        }
    }
}
?>
