<?php
namespace qeywork;

/**
 * Renders a form, based on a model and a form visual 
 */
class FormRenderer {
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
    public function __construct(IFormVisual $formVisual) {
        $this->errors = null;        
        
        $this->method = 'POST';
        $this->ajax = false;
        $this->multiPart = false;
        
        $this->formVisual = $formVisual;
    }
    
    public function setAction(Url $action) {
        $this->action = $action;
    }
    
    public function fillFormData(FormData $formData) {
        $this->formData = $formData;
    }
    
    protected function createHiddenData() {
        $hidden = $this->formData->getHiddenFields();
        $rendered = new HtmlEntityList();
        foreach ($hidden as $field) {
            /* @var $field FormHiddenField */
            $rendered->add($field->render());
        }
        return $rendered;
    }
    
    /**
     * Builds form
     */
    public function render()
    {
        $formData = $this->formData;
        
        $this->attributes['action'] = $this->action;
        $this->attributes['method'] = $this->method;
        $class = explode('\\', get_class($formData));
        $this->attributes['id'] = end($class);
        $this->attributes['data-qey-form'] = $this->ajax ? 'ajax' : 'simple-form';
        if (! $this->multiPart) {
            unset($this->attributes['multipart']);
        }
        
        //create hidden input
        $hiddenData = $this->createHiddenData();
        
        $entryList = new HtmlEntityList();
        $fields = $formData->getFields();
        foreach ($fields as $field) {
            if (! $field instanceof FormField) {
                continue;
            }
            
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
