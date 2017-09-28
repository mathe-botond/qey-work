<?php
namespace QeyWork\View\Forms\Actions;
use BadMethodCallException;
use QeyWork\Common\ClientDataException;
use QeyWork\Common\IAction;
use QeyWork\Entities\Entity;
use QeyWork\View\Forms\Fields\FormData;
use QeyWork\View\Forms\Fields\PostFormData;

/**
 *Operations to help form building, data and error handling 
 */
abstract class FormAction implements IAction {
    
    /** @var PostFormData */
    protected $form;
    /** @var SubmittedFormHandler */
    private $formHandler;
    
    
    public function __construct(SubmittedFormHandler $submittedFormHandler) {
        $this->formHandler = $submittedFormHandler;
        $this->form = $submittedFormHandler->getSubmittedForm();
    }

    protected function redirectForward() {
        $this->formHandler->redirect();
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
            $result = $this->executeOnEntity($this->form->getEntity());
        
            $this->formHandler->removeSubmittedForm();
        
            //return
            if ($this->formHandler->isAjaxRequest()) { 
                return $result;
            } else {
                $this->redirectForward();
            }
        } else {
            //there were some errors
            //$this->formCollection->overwrite($this->formId, $this->form);
            
            if ($this->formHandler->isAjaxRequest()) { 
                return $result;
            } else {
                $prg = $this->form->getPrg();
                $this->formHandler->goBack($prg);
            }
        }
    }
    
    /**
     * Use this function to modify your entity before validating it
     *   (e.g. remove a validator)
     */
    protected function beforeValidate(FormData $form) {
    }
    
    protected function validate(FormData $form) {
        return true;
    }
    
    public abstract function executeOnEntity(Entity $entity);
}
