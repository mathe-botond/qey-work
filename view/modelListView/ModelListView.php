<?php
namespace qeywork;

class ModelListView {
    protected $modelList;
    protected $visual;
    protected $display;

    public function __construct(ModelDisplay $display, IModelListViewVisual $visual) {
        $this->visual = $visual;
        $this->display = $display;
    }
    
    public function setModelList(ModelList $modelList) {
        $this->modelList = $modelList;
        $type = $this->modelList->getModelType();
        try {
            $this->display->injectModel($type);
        } catch(q\TypeException $e) {
            throw new ArgumentException('ModelList type incompatible with modelDisplay type: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        $fields = $this->display->getFields();
        
        $headerCells = new HtmlEntityList();
        foreach ($fields as $field) {
            if ($field->isVisible()) {
                $headerCells[] = $this->visual->headerCell($field->label);
            }
        }
        
        $header = $this->visual->header($headerCells);
        
        $rows = new HtmlEntityList();
        foreach ($this->modelList as $model) {
            $this->display->injectModel($model);
            /* @var $model Model */
            $cells = new HtmlEntityList();
            foreach ($this->display->getFields() as $field) {
                $value = $field->render();
                $cells[] = $this->visual->cell($value);
            }
            $rows[] = $this->visual->entry($model->getId(), $cells);
        }
        return $this->visual->base($header, $rows);
    }
}
