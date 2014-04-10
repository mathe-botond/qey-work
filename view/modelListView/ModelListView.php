<?php
namespace qeywork;

class ModelListView {
    protected $modelList;
    protected $visual;
    protected $display;
    
    /**
     * Constructor of this class
     * @param string $name
     * @param ModelEntity $model 
     * @param CModelManager $modelManager
     */
    public function __construct(ModelList $modelList, ModelDisplay $display, IModelListViewVisual $visual = null) {
        if ($visual == null) {
            $visual = new ModelListViewVisual();
        }
        
        $this->modelList = $modelList;
        $this->visual = $visual;
        $this->display = $display;
        
        $type = $this->modelList->getModelType();
        try {
            $display->injectModel($type);
        } catch(q\TypeException $e) {
            throw new ArgumentException('ModelList type incompatible with modelDisplay type: ' . $e->getMessage());
        }
    }
    
    /**
     * @return string
     */
    public function render()
    {
        $fields = $this->display->getFields();
        
        $headerCells = new HtmlEntityList();
        foreach ($fields as $field) {
            if ($field instanceof Field) {
                if ($field->displayControl !== null && $field->displayControl->isVisible()) {
                    $headerCells[] = $this->visual->headerCell($field->label);
                }
            }
        }
        
        $header = $this->visual->header($headerCells);#
        
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
