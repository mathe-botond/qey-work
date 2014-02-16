<?php
namespace qeywork;

class ModelView implements IRenderable {
    /**
     * @var ModelEntity $model
     */
    protected $model;
    public $visual;
    /**
     * @param ModelEntity $model 
     */
    public function __construct(Model $model, IModelViewVisual $visual) {
        $this->model = $model;
        $this->visual = $visual;
    }
    
    /**
     * @param IModelViewVisual $viewVisual
     * @return string 
     */
    public function render() {
        
        if (! $this->visual instanceof IModelViewVisual) {
            throw new ArgumentException('viewVisual must be an instance of IModelViewVisual');
        }
        
        $entryList = new HtmlEntityList();
        foreach ($this->model as $field) {
            if (! $field instanceof Field)
                continue;
            
            if (! $field->displayControl->isVisible())
                continue;
            
            $label = $field->getName();
            $value = $field->displayControl->render();
            
            $entryList[] = $this->visual->entry($label, $value);
        }
        
        return $this->visual->base($entryList);;
    }
}
?>