<?php
namespace qeywork;

/**
 *Operations to help form building, data and error handling 
 */
abstract class FormAction implements IAction {
    /** @var Params */
    private  $params;
    /** @var Cache */
    private $cache;
    /** @var Locations */
    private $locations;
    
    private $formId;
    
    /** @var FormData */
    protected $form;
    /** @var FormCollection */
    protected $formCollection;
    
    public function isPostFormSubmitted(Params $params) {
        return $params->exists('qey-form-id');
    }
    
    public function __construct(ResourceCollection $resources, FormCollection $forms) {
        $params = $resources->getParams();
        if ($this->isPostFormSubmitted($params)) { 
            $this->formId = $params->qeyFormId;
        } else {
            throw new ClientDataException('No form id found');
        }
        
        $this->formCollection = $forms;
        $this->formCollection->setSubmittedFormId($this->formId);
        $this->form = $this->formCollection->getSubmittedForm();
        if ($this->form == false) {
            throw new ClientDataException('Form data is missing');
        }
        
        $params->getFormDataAsModel($this->form);
        
        $this->params = $resources->getParams();
        $this->cache = $resources->getCache();
        $this->locations = $resources->getLocations();
    }
    
    /**
     * Form gateway, entry point of form handling
     * @return string ajax response, or error list
     * @throws ClientDataException on invalid user action
     * @throws BadMethodCallException when finding corrupted form
     */
    public function execute()
    {
        $this->beforeValidate($this->form);
        $result = $this->form->validate();
        if ($result === true) {
            $result = $this->validate($this->form);
        }
        
        if ($result === true) { //no validation error occured
            $result = $this->executeOnModel($this->form->getModel());
        
            //cleanup
            $this->formCollection->remove($this->formId);
        
            //return
            if ($this->params->exists('qey-form-ajax') && $this->params->qeyFormAjax === 'true') { 
                return $result;
            } else {
                $prg = $this->form->getPrg();
                $this->locations->redirect($prg->getRedirect());
            }
        } else { //there were some errors
            //$this->form->setErrors($result);
            $this->formCollection->overwrite($this->formId, $this->form);
            
            if ($this->params->exists('qey-form-ajax') && $this->params->qeyFormAjax === 'true') { 
                return $result;
            } else {
                $prg = $this->form->getPrg();
                $this->locations->redirect($prg->getPageAddress());
            }
        }
    }
    
    /**
     * Use this function to modify your model before validating it
     *   (e.g. remove a validator)
     */
    protected function beforeValidate(FormData $form) {
    }
    
    protected function validate(FormData $form) {
        return true;
    }
    
    public abstract function executeOnModel(Model $model);
}
?>