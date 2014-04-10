<?php
namespace qeywork;

/**
 * @author Dexx
 */
class BasicModelListingPage extends Page {

    /** @var ModelListView */
    protected $listView;

    public function __construct(
            IModelManagerFactory $factory,
            DB $db) { 
        $type = $factory->getListingModel();
        
        $listViewVisual = $factory->getListViewVisual();
        
        $models = $db->search($type);
        $this->listView = new ModelListView($models, $listViewVisual);;
    }

    public function render() {
        return $this->listView->render();
    }
}
