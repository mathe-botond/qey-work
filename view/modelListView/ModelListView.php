<?php
namespace qeywork;

class ModelListView {
    protected $modelList;
    protected $visual;
    
    /**
     * Constructor of this class
     * @param string $name
     * @param ModelEntity $model 
     * @param CModelManager $modelManager
     */
    public function __construct(ModelList $modelList, IModelListViewVisual $visual = null) {
        if ($visual == null) {
            $visual = new ModelListViewVisual();
        }
        
        $this->modelList = $modelList;
        $this->visual = $visual;
    }
    
    /**
     * @return string
     */
    public function render()
    {
        $type = $this->modelList->getType();
        
        $headerCells = new HtmlEntityList();
        foreach ($type as $field) {
            if ($field instanceof Field) {
                if ($field->displayControl !== null && $field->displayControl->isVisible()) {
                    $headerCells[] = $this->visual->headerCell($field->label);
                }
            }
        }
        
        $header = $this->visual->header($headerCells);#
        
        $rows = new HtmlEntityList();
        foreach ($this->modelList as $model) {
            /* @var $model Model */
            $cells = new HtmlEntityList();
            foreach ($model as $field) {
                if ($field instanceof Field) {
                    $value = $field->displayControl->render();
                    $cells[] = $this->visual->cell($value);
                }
            }
            $rows[] = $this->visual->entry($model->getId(), $cells);
        }
        
        return $this->visual->base($header, $rows);
    }
}
?>