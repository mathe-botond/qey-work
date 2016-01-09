<?php
namespace qeywork;

/**
 * Renders a form, based on a entity and a form visual
 */
class FormRenderer {
    protected $attributes = array(
        'id' => '',
        'action' => '',
        'method' => '',
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
    
    protected function createHiddenData(HtmlBuilder $h) {
        $hidden = $this->formData->getHiddenFields();
        $rendered = new HtmlObjectList();
        foreach ($hidden as $field) {
            /* @var $field FormHiddenField */
            $rendered->add($field->render($h));
        }
        return $rendered;
    }
    
    /**
     * Builds form
     */
    public function render(HtmlBuilder $h) {
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
        $hiddenData = $this->createHiddenData($h);
        
        $fieldSet = $formData->getFieldSet();
        $fieldSetRenderer = new FieldSetRenderer($this->formVisual);
        $fieldSetRenderer->setFieldSet($fieldSet);
        $entryList = $fieldSetRenderer->render($h);
        
        $submit = $formData->getSubmit()->render($h);
        
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
