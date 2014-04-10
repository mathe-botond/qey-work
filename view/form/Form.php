<?php
namespace qeywork;

/**
 * Renders a form, based on a model and a form visual 
 */
class Form {
    protected $attributes = array(
        'id' => '',
        'action' => '',
        'method' => '',
        'enctype' => 'multipart/form-data',
        'data-qey-form' => ''
    );
    
    /** @var FormData */
    protected $formData;
    
    protected $method;
    protected $ajax;
    protected $multiPart;
    protected $action;
    
    public $formVisual;
    
    /**
     * Constructor of this class
     * @param IFormVisual $formVisual
     */
    public function __construct(FormData $formData, Url $action, IFormVisual $formVisual = null) {
        $this->errors = null;
        $this->formData = $formData;
        
        $this->action = $action;
        $this->method = 'POST';
        $this->ajax = false;
        $this->multiPart = false;
        
        if ($formVisual == null) {
            $this->formVisual = new FormVisualUsingList();
        } else {
            $this->formVisual = $formVisual;
        }
    }
    
    protected function createHiddenData() {
        return null;
    }
    
    /**
     * Builds form
     */
    public function render()
    {
        $formData = $this->formData;
        
        $this->attributes['action'] = $this->action;
        $this->attributes['method'] = $this->method;
        $this->attributes['id'] = get_class($formData);
        $this->attributes['data-qey-form'] = $this->ajax ? 'ajax' : 'simple-form';
        if (! $this->multiPart) {
            unset($this->attributes['multipart']);
        }
        
        //create hidden input
        $hiddenData = $this->createHiddenData();
        
        $entryList = new HtmlEntityList();
        $fields = $formData->getFields();
        foreach ($fields as $field) {
            if (! $field instanceof FormField)
                continue;
            
            $input = $field->render();
            
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
            
            $entryList[] = $this->formVisual->entry(
                'row-' . $key,
                $rowClass,
                $field->label,
                $input,
                $field->comment,
                $message
            );
        }
        
        $submit = $formData->getSubmit()->render();
        
        $form = $this->formVisual->base(
            $this->attributes,
            $hiddenData,
            $entryList,
            $submit
        );
        
        return $form;
    }
    
    public function getFormData() {
        return $this->formData;
    }
}
