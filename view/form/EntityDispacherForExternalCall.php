<?php
namespace qeywork;

/**
 * Dispatches client entity for the current form
 */
class EntityDispacherForExternalCall implements IAction {
    const NAME = 'entity-dispacher';
    
    protected $id;
    /** @var PostFormCollection $formCollection */
    protected $formCollection;
    
    /** @var ClientEntityDispatcher $dispatcher */
    protected $dispatcher;
    
    public function __construct(Params $params, Session $session) {
        $this->id = $params->formId;
        
        $this->formCollection = new PostFormCollection($session);
        
        $this->dispatcher = new ClientEntityDispatcher();
    }
    
    public function execute() {
        /* @var $form PostFormRenderer */
        $form = $this->formCollection->get($this->id);
        
        $dispatcher = new ClientEntityDispatcher();
        $dispatcher->addForm($form);
        echo $dispatcher->output();
    }
}
