<?php
namespace qeywork;

/**
 * @author Dexx
 */
class Model implements IModelEntity {
    protected $id;
    
    /** @var IPersistentData $persistanceData Data concerning persistance */
    public $persistanceData; 
    
    public function setPersistanceData(IPersistentData $persistanceData) {
        $this->persistanceData = $persistanceData;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        return $this->id = $id;
    }
    
    public function toClientModel() {
        $descriptor = array();
        foreach ($this as $field) {
            if ($field instanceof Field) {
                $descriptor[$field->getName()] = $field->toClientModel();
            }
        }
        return $descriptor;
    }
}

?>
