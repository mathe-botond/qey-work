<?php
namespace qeywork;

/**
 * @author Dexx
 */
class InputAction extends FormAction {
    /** @var ModelDbController */
    protected $db;
    
    public function __construct(ResourceCollection $resources) {
        parent::__construct($resources);
        $this->db = $resources->getDb();
    }
    
    public function executeOnModel(Model $model) {
        $persistance = new ModelDbController($model, $this->db);
        $persistance->insert();
    }
}
?>
