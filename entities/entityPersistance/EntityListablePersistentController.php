<?php
namespace qeywork;

/**
 * Description of DataListPersistentController
 *
 * @author Dexx
 */
abstract class EntityListablePersistentController implements IPersistence {
    protected $id;
    
    public function save() {
        if ($this->id === null) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }
}
