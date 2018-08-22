<?php
namespace QeyWork\View\ModelView;

use QeyWork\Common\ArgumentException;
use QeyWork\Entities\Entity;
use QeyWork\Entities\Fields\Field;
use QeyWork\View\Html\HtmlBuilder;
use QeyWork\View\Html\HtmlObjectList;
use QeyWork\View\IRenderable;

class EntityView implements IRenderable {
    /** @var Entity $entity */
    protected $entity;

    public $visual;

    /** @param Entity $entity */
    public function __construct(Entity $entity, IEntityViewVisual $visual) {
        $this->entity = $entity;
        $this->visual = $visual;
    }

    /**
     * @param HtmlBuilder $h
     * @return string
     * @throws ArgumentException
     * @internal param IEntityViewVisual $viewVisual
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
