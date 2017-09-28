<?php
namespace QeyWork\View\Forms\Actions;
use QeyWork\Common\Addresses\Locations;
use QeyWork\Common\ClientDataException;
use QeyWork\Resources\Request;
use QeyWork\View\Forms\Fields\FormData;
use QeyWork\View\Forms\Post\PostFormCollection;

/**
 * @author Dexx
 */
class SubmittedFormHandler {
    const FORM_ID = 'qey-form-id';

    /** @var Request */
    private $params;
    /** @var Locations */
    private $locations;

    /** @var PostFormCollection */
    private $forms;
    
    private $formId = null;
    /** @var FormData */
    protected $form;

    public function __construct(PostFormCollection $forms,
                                Request $params,
                                Locations $locations) {
        $this->forms = $forms;
        $this->params = $params;
        $this->locations = $locations;
    }
    
    public function isPostFormSubmitted() {
        return $this->params->exists(self::FORM_ID);
    }
    
    public function getSubmittedForm() {
        if ($this->isPostFormSubmitted()) {
            $this->formId = $this->params->qeyFormId;
        } else {
            throw new ClientDataException('No form id found');
        }
        
        $this->forms->setSubmittedFormId($this->formId);
        $this->form = $this->forms->getSubmittedForm();
        if ($this->form == false) {
            throw new ClientDataException('Form data is missing');
        }
        
        $this->params->getFormDataAsEntity($this->form);
        return $this->form;
    }
    
    public function removeSubmittedForm() {
        $this->forms->remove($this->formId);
    }
    
    public function isAjaxRequest() {
        return $this->params->exists('qey-form-ajax') && $this->params->qeyFormAjax === 'true';
    }
    
    public function goBack() {
        $prg = $this->form->getPrg();
        $this->locations->redirect($prg->getPageAddress());
    }
    
    public function redirect() {
        $prg = $this->form->getPrg();
        $this->locations->redirect($prg->getRedirect());
    }
}
