<?php
namespace qeywork;

class BasicModelManagerFactory extends AbstractModelManagerFactory {
    /** @var ResourceCollection */
    protected $resources;
    protected $meta;
    
    protected $listingPage;
    protected $formPage;
    
    //controllers
    /** @var IAction */
    protected $inputAction;
    /** @var IAction */
    protected $modifyAction;
    /** @var IAction */
    protected $modelManager;
    /** @var IAction */
    protected $removalAction;
    
    public function __construct(Model $model, ResourceCollection $resources, QeyMeta $meta = null) {
        parent::__construct($model);
        $this->resources = $resources;
        $this->meta = $meta;
    }
    
    public function getListingPage() {
        if ($this->listingPage == null) {
            $this->listingPage = new BasicModelListingPage($this,
                    $this->resources->getDb());
        }
        return $this->listingPage;
    }

    public function getModelFormPage($mode, $id) {
        $formFactory = new MMSFormFactory($this->getPathCollection(),
                $this->resources,
                $this->meta);
        
        if ($this->formPage == null) {
            $this->formPage = new BasicModelFormPage($this, $formFactory, $mode, $id);;
        }
        return $this->formPage;
    }
    
    /**
     * @return IAction
     */
    public function getInputAction() {
        if ($this->inputAction !== null) {
            return $this->inputAction;
        } else {
            return new InputAction($this->resources);
        }
    }
    
    /**
     * @return IAction 
     */
    public function getModifyAction() {
        if ($this->modifyAction !== null) {
            return $this->modifyAction;
        } else {
            return new ModifyAction($this->resources);
        }
    }
    
    /**
     * @return IAction 
     */
    public function getRemovalAction() {
        if ($this->removalAction !== null) {
            return $this->modifyAction;
        } else {
            return new RemovalAction($this->getRemovalModel(),
                    $this->resources->getDb(),
                    $this->resources->getParams());
        }
    }
    
    /**
     * @return CModelManager 
     */
    public function getModelManager($model = null) {
        if ($model == null) {
            $model = $this->defaultModel;
        }
        
        if ($this->modelManager !== null) {
            return $this->modelManager;
        } else {
            return new CModelManager($this->resources->getDb(),
                    $model,
                    $this->pathCollection);
        }
    }
}
