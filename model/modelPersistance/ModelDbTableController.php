<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ModelDbTableController {
    private $db;
    
    protected $model;
    
    public function __construct(DB $db, Model $model) {
        $this->model = $model;
        $this->db = $db;
        
        $persistance = $model->persistanceData;
        if ($persistance == null || $persistance->getPersistannceName() != DB::PERSISTANCE_NAME) {
            throw new ModelException('Missing or invalid model persistance.');
        }
    }
    
    public function createModelTable() {
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
                $this->db->execute($query);
            } else if ($checker->isFieldMissing()) {
                throw new ModelException("Table ($tableName) corrupted. Please remove it manually.");
            }
            
            return $this->db->search($this->model);
        }
    }
}
