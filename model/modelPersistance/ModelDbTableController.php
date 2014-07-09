<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ModelDbTableController {
    private $db;
    
    protected $model;
    
    public function __construct(DB $db, Model $model = null) {
        $this->db = $db;
        
        if ($model != null) {
            $this->setModel($model);
        }
    }
    
    public function setModel(Model $model) {
        $this->model = $model;
        
        $persistance = $model->persistanceData;
        if ($persistance == null || $persistance->getPersistannceName() != DB::PERSISTANCE_NAME) {
            throw new ModelException('Missing or invalid model persistance.');
        }
    }
    
    public function createModelTable() {
        if ($this->model == null) {
            throw new \BadMethodCallException('No model was set');
        }
        
        $table = $this->model->persistanceData->getNameOfPersistanceObject();
        $fieldList[0] = 'id INT NOT NULL AUTO_INCREMENT';
        $keys[0] = 'PRIMARY KEY (id)';
        $fields = $this->model->getFields();
        foreach ($fields as $field) {
            if ($field instanceof TypedField) {
                $fieldList[] = $field->getTypeString();
            }
            if ($field instanceof ReferenceField) {
                $name = $field->getName();
                $refenrencedTable = $field->getModelType()
                        ->persistanceData->getNameOfPersistanceObject();
                $keys[] = "FOREIGN KEY (`$name`)
                    REFERENCES `$refenrencedTable`(`id`)
                    ON UPDATE RESTRICT ON DELETE RESTRICT";
            }
        }
        $fieldListString = implode(',', $fieldList) . ', ' . implode(',' , $keys);
        $query = "CREATE TABLE IF NOT EXISTS `$table` ($fieldListString) ENGINE=InnoDB";
        return $this->db->execute($query);
    }
    
    public function getDataOrCreateTable($query = null) {
        if ($this->model == null) {
            throw new \BadMethodCallException('No model was set');
        }
        
        $tableName = $this->model->persistanceData->getNameOfPersistanceObject();
        try {
            return $this->db->search($this->model);
        } catch (DbException $e) {
            $checker = new ModelDbChecker($this->db);
            $checker->check($this->model);
            if ($checker->isTableMissing()) {
                if (null != $query) {
                    $this->db->execute($query);
                } else {
                    $this->createModelTable();
                }
            } else if ($checker->isFieldMissing()) {
                throw new ModelException("Table ($tableName) corrupted. Please remove it manually.");
            }
            
            return $this->db->search($this->model);
        }
    }
}
