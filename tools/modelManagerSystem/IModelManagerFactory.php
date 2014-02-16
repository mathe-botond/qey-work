<?php
namespace qeywork;

/**
 * Set's the modelManager up
 *
 * @author Dexx
 */
interface IModelManagerFactory {
    //models
    /**
     * @return Model
     */
    public function getDefaultModel();
    /**
     * @return Model
     */
    public function getInsertModel();
    /**
     * @return Model
     */
    public function getModifyModel();
    /**
     * @return Model
     */
    public function getListingModel();
    /**
     * @return Model
     */
    public function getRemovalModel();
    
    //views
    /**
     * @return Page 
     */
    public function getListingPage();
    /**
     * @param string $mode
     * @param string $id
     * @return Page 
     */
    public function getModelFormPage($mode, $id);
    
    //controller
    /**
     * @return IAction
     */
    public function getInputAction();
    /**
     * @return IAction 
     */
    public function getModifyAction();
    /**
     * @return IAction 
     */
    public function getModelManager();
    
    //other
    public function getName();
    /**
     * @return ModelListViewVisual 
     */
    public function getListViewVisual();
    /**
     * @return MMSPathCollection 
     */
    public function getPathCollection();
    public function createPathCollection(Url $basepath);
}

?>
