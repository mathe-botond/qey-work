<?php
namespace qeywork;

/**
 * Dispatches client model for the current form
 */
class ModelDispacherForExternalCall implements IAction {
    const NAME = 'model-dispacher';
    
    protected $id;
    /** @var FormCollection $formCollection */
    protected $formCollection;
    
    /** @var ClientModelDispatcher $dispatcher */
    protected $dispatcher;
    
    public function __construct(Params $params, Session $session) {
        $this->id = $params->formId;
        
        $this->formCollection = new FormCollection($session);
        
        $this->dispatcher = new ClientModelDispatcher();
    }
    
    public function execute() {
        /* @var $form PostForm */
        $form = $this->formCollection->get($this->id);
        
        $dispatcher = new ClientModelDispatcher();
        $dispatcher->addForm($form);
        echo $dispatcher->output();
    }
}
