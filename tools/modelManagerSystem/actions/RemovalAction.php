<?php
namespace qeywork;

/**
 * @author Dexx
 */
class RemovalAction implements IAction{
    protected $persistance;
    protected $id;
    
    public function __construct(Model $removalModel, DB $db, Params $params) {
        $this->id = $params->id;
        $this->persistance = new ModelDbController($removalModel, $db);
    }

    public function execute() {
        try {
            $this->persistance->load($this->id);
            $this->persistance->remove();
        } catch (ModelException $e) {
            return 'Action failed';
        }
    }
}
