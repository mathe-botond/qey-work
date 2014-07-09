<?php
namespace qeywork;

/**
 * Form datas handled by the framework arestored in this collection  
 */
class FormCollection extends SessionCacheble {
    const SESSION_KEY = 'formCollection';

    /** @var SmartArray */
    protected $forms;
    protected $currentFormID;

    protected function getSessionKey() {
        return self::SESSION_KEY;
    }
    
    public function __construct(Session $session)
    {
        $this->forms = new SmartArray();
        $this->currentFormID = null;
        
        parent::__construct($session);
        
        //var_dump($this->forms->getArray());
    }
    
    /**
     * @param FormData $form
     * @return int id 
     */
    public function add(PostFormData $form) {
        do {
            $id = rand(10000, 99999);
        } while ($this->forms->offsetExists($id));
        $this->forms[$id] = $form;
        return $id;
    }
    
    public function overwrite($id, PostFormData $form) {
        if (! $this->forms->offsetExists($id)) {
            throw new \BadFunctionCallException('Can\'t overwrite what doesn\'t exist');
        }
        $this->forms[$id] = $form;
    }
    
    /**
     * @param int $id
     * @return FormData
     * @throws ArgumentException 
     */
    public function get($id) {
        if ($this->forms->offsetExists($id)) {
            return $this->forms[$id];
        } else {
            throw new ArgumentException('Form with id ' . $id . ' is not defined');
        }
    }
    
    public function remove($id) {
        if ($this->forms->offsetExists($id)) {
            unset($this->forms[$id]);
        } else {
            throw new ArgumentException('Form with id ' . $id . ' is not defined');
        }
    }
    
    public function wasFormSubmitted() {
        return $this->currentFormID !== null;
    }

    public function setSubmittedFormId($id) {
        $this->currentFormID = intval($id);
    }

    /**
     * @return FormData
     */
    public function getSubmittedForm() {
        return $this->get( $this->currentFormID );
    }
    
    public function cleanSubmitted() {
        $this->currentFormID = null;
    }
}
