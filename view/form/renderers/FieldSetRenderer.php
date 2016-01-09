<?php
namespace qeywork;

/**
 * @author Dexx
 */
class FieldSetRenderer implements IRenderable {

    /** @var IFormVisual */
    private $formVisual;
    
    /** @var FieldSet */
    private $fieldSet;
    
    /** @var HtmlBuilder */
    private $h;

    public function __construct(IFormVisual $formVisual) {
        $this->formVisual = $formVisual;
    }

    public function setFieldSet(FieldSet $fieldSet) {
        $this->fieldSet = $fieldSet;
    }
    
    public function render(HtmlBuilder $h) {
        $this->h = $h;
        
        $entryList = new HtmlObjectList();
        
        foreach ($this->fieldSet->getFields() as $field) {
            if (! $field instanceof FormField) {
                continue;
            }
            
            $entryList[] = $this->renderField($field);
        }
        
        $fieldSetRenderer = new FieldSetRenderer($this->formVisual);
        foreach ($this->fieldSet->getChildFieldSets() as $fieldSet) {
            $fieldSetRenderer->setFieldSet($fieldSet);
            $childEntries = $fieldSetRenderer->render($h);
            if ($fieldSet->isSeamless()) {
                $entryList[] = $childEntries;
            } else {
                $entryList[] = $this->formVisual->fieldSet(
                        $childEntries,
                        $fieldSet->getTitle(),
                        $fieldSet->getClass());
            }
        }
        
        return $entryList;
    }
    
    private function renderField(FormField $field) {
        $input = $field->render($this->h);
            
        $key = $field->getName();
        if (! $field->isValid()) {
            $errors = $field->getErrors();
            $message = $this->formVisual->message('row-' . $field->getName() . '-message-field',
                'form-validation-notification validation-error',
                $errors
            );
            $rowClass = 'error';
        } else {
            $message = null;
            $rowClass = '';
        }

        return $this->formVisual->entry(
            'row-' . $key,
            $rowClass,
            $field->label,
            $input,
            $field->comment,
            $message
        );
    }
}
