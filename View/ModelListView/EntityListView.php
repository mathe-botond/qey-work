<?php
namespace QeyWork\View\ModelListView;

use QeyWork\Common\ArgumentException;
use QeyWork\Common\TypeException;
use QeyWork\Entities\Entity;
use QeyWork\Entities\EntityList;
use QeyWork\View\Html\HtmlBuilder;
use QeyWork\View\Html\HtmlObjectList;
use QeyWork\View\ModelDisplay\EntityDisplay;

class EntityListView {
    protected $entityList;
    protected $visual;
    protected $display;

    public function __construct(EntityDisplay $display, IEntityListViewVisual $visual) {
        $this->visual = $visual;
        $this->display = $display;
    }
    
    public function setEntityList(EntityList $entityList) {
        $this->entityList = $entityList;
        $type = $this->entityList->getEntityType();
        try {
            $this->display->injectEntity($type);
        } catch(TypeException $e) {
            throw new ArgumentException('EntityList type incompatible with entityDisplay type: ' . $e->getMessage());
        }
    }
    
    public function render(HtmlBuilder $h)
    {
        $fields = $this->display->getFields();
        
        $headerCells = new HtmlObjectList();
        foreach ($fields as $field) {
            if ($field->isVisible()) {
                $headerCells[] = $this->visual->headerCell($field->label);
            }
        }
        
        $header = $this->visual->header($headerCells);
        
        $rows = new HtmlObjectList();
        foreach ($this->entityList as $entity) {
            $this->display->injectEntity($entity);
            /* @var $entity Entity */
            $cells = new HtmlObjectList();
            foreach ($this->display->getFields() as $field) {
                $value = $field->render($h);
                $cells[] = $this->visual->cell($value);
            }
            $rows[] = $this->visual->entry($entity->getId(), $cells);
        }
        return $this->visual->base($header, $rows);
    }
}
