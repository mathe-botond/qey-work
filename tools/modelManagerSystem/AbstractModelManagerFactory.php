<?php
namespace qeywork;

/**
 * @author Dexx
 */
abstract class AbstractModelManagerFactory implements IModelManagerFactory{
    //models
    /** @var Model */
    protected $defaultModel;
    /** @var Model */
    protected $insertModel;
    /** @var Model */
    protected $modifyModel;
    /** @var Model */
    protected $listingModel;
    /** @var Model */
    protected $removalModel;
    
    //other
    /** @var MMSPathCollection */
    protected $pathCollection;
    /** @var IModelListViewVisual */
    protected $listViewVisual;
    
    /**
     * @param ModelEntity $model
     * @param MMSPathCollection $pathCollection
     */
    public function __construct(Model $model) {
        $this->defaultModel = $model;
        $this->insertModel = null;
        $this->modifyModel = null;
        $this->searchModel = null;
        $this->listingModel = null;
        $this->removalModel = null;
        
        $this->listViewVisual = null;
    }
    
    //models
    /**
     * @return Model
     */
    public function getDefaultModel() {
        return $this->defaultModel;
    }
    
    public function setInsertModel(Model $model) {
        $this->insertModel = $model;
    }
    
    /**
     * @return Model
     */
    public function getInsertModel() {
        if ($this->insertModel !== null) {
            return $this->insertModel;
        } else {
            return $this->defaultModel;
        }
    }
    
    public function setModifyModel(Model $model) {
        $this->modifyModel = $model;
    }
    
    /**
     * @return Model
     */
    public function getModifyModel() {
        if ($this->modifyModel !== null) {
            return $this->modifyModel;
        } else {
            return $this->defaultModel;
        }
    }
    
    public function setRemovalModel(Model $model) {
        $this->removalModel = $model;
    }
    
    /**
     * @return ModelEntity 
     */
    public function getRemovalModel() {
        if ($this->removalModel !== null) {
            return $this->removalModel;
        } else {
            return $this->defaultModel;
        }
    }
    
    public function setSearchModel(Model $model) {
        $this->searchModel = $model;
    }

    /**
     * @return ModelEntity 
     */
    public function getSearchModel() {
        if ($this->searchModel !== null) {
            return $this->searchModel;
        } else {
            return $this->defaultModel;
        }
    }
    
    public function setListingModel(Model $model) {
        $this->listingModel = $model;
    }
    
    /**
     * @return ModelEntity 
     */
    public function getListingModel() {
        if ($this->listingModel !== null) {
            return $this->listingModel;
        } else {
            return $this->defaultModel;
        }
    }
    
    //other    
    /**
     * @return string 
     */
    public function getName() {
        return $this->pathCollection->name;
    }
    
    public function getListViewVisual() {
        if ($this->listViewVisual == null) {
            return new SimpleModelListViewVisualWithActions($this->pathCollection->editPage, $this->pathCollection->removalOperation);
        } else {
            return $this->listViewVisual;
        }
    }
    
    public function setListViewVisual($visual) {
        $this->listViewVisual = $visual;
    }
    
    /**
     * @return MMSPathCollection 
     */
    public function getPathCollection() {
        return $this->pathCollection;
    }
    
    public function createPathCollection(Url $basepath, $name = '') {
        $this->pathCollection = new MMSPathCollection($basepath, $name);
    }
}

?>
