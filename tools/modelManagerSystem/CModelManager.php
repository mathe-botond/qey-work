<?php
namespace qeywork;

/**
 * General controller for the CModelManager
 *
 * @author Dexx, hupu
 */
class CModelManager {

    public $model;
    protected $db;
    /**
     *
     * @param ModelEntity $model          
     */
    public function __construct(DB $db, $model) {
        $this->db = $db;
        $this->model = $model;
    }

    /**
     * @return ModelList
     */
    public function getAll() {
        return $this->getList();
    }

    /**
     * @return ModelList
     */
    public function getList($conditions = array(), $order = null, $orderDir = DB::ORDER_ASC, $limit = null) {
        return $this->db->search($this->model, $conditions, $order, $orderDir, $limit);
    }

    public function getById($id = -1) {
        $modelList = $this->getList(array('id' => $id));
        if ($modelList->count() !== 1) {
            return $this->model;
        } else {
            return $modelList[0];
        }
    }

    public function getByName($name = "") {
        $modelList = $this->getList(array('name' => $name));
        if ($modelList->count() < 1) {
            return $this->model;
        } elseif ($modelList->count() > 1) {
            return $modelList;
        } else {
            return $modelList[0];
        }
    }
}
