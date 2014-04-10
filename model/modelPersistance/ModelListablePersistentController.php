<?php
namespace qeywork;

/**
 * Description of DataListPersistentController
 *
 * @author Dexx
 */
abstract class ModelListablePersistentController implements IModelPersistanceController {
    protected $id;
    
    public function save() {
        if ($this->id === null) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }
    
    public abstract function insert();
    public abstract function update();
}
