<?php
namespace qeywork;

class EntityView implements IRenderable {
    /**
     * @var EntityEntity $entity
     */
    protected $entity;
    public $visual;
    /**
     * @param EntityEntity $entity
     */
    public function __construct(Entity $entity, IEntityViewVisual $visual) {
        $this->entity = $entity;
        $this->visual = $visual;
    }
    
    /**
     * @param IEntityViewVisual $viewVisual
     * @return string 
     */
    public function render(HtmlBuilder $h) {
        
        if (! $this->visual instanceof IEntityViewVisual) {
            throw new ArgumentException('viewVisual must be an instance of IEntityViewVisual');
        }
        
        $entryList = new HtmlObjectList();
        foreach ($this->entity as $field) {
            if (! $field instanceof Field)
                continue;
            
            if (! $field->displayControl->isVisible())
                continue;
            
            $label = $field->getName();
            $value = $field->displayControl->render($h);
            
            $entryList[] = $this->visual->entry($label, $value);
        }
        
        return $this->visual->base($entryList);;
    }
}
