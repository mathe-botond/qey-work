<?php
namespace qeywork;

/**
 * @author Dexx
 */
class DbTableCreator {
    private $db;
    
    /** @var Model */
    protected $model;
    
    public function __construct(DB $db) {
        $this->db = $db;
    }
    
    protected function checkModel(Model $model) {
        $persistance = $model->getPersistenceData();
        if ($persistance == null || $persistance->getPersistannceName() != DB::PERSISTANCE_NAME) {
            throw new ModelException('Missing or invalid model persistance.');
        }
    }
    
    public function createTable(Model $model) {
        $this->checkModel($model);
        
        $table = $model->getPersistenceData()->getNameOfPersistenceObject();
        $fieldList[0] = 'id INT NOT NULL AUTO_INCREMENT';
        $keys[0] = 'PRIMARY KEY (id)';
        
        $fields = $model->getFields();
        foreach ($fields as $field) {
            if ($field instanceof TypedField) {
                $fieldList[] = $field->getTypeString();
            }
            if ($field instanceof ReferenceField) {
                $name = $field->getName();
                $refenrencedTable = $field->getModelType()
                        ->getPersistenceData()->getNameOfPersistenceObject();
                $keys[] = "FOREIGN KEY (`$name`)
                    REFERENCES `$refenrencedTable`(`id`)
                    ON UPDATE RESTRICT ON DELETE RESTRICT";
            }
        }
        $fieldListString = implode(',', $fieldList) . ', ' . implode(',' , $keys);
        $query = "CREATE TABLE IF NOT EXISTS `$table` ($fieldListString) ENGINE=InnoDB";
        return $this->db->execute($query);
    }
    
    public function getDataOrCreateTable(Model $model, $query = null) {
        $tableName = $model->getPersistenceData()->getNameOfPersistenceObject();
        
        try {
            return $this->db->search($model);
        } catch (DbException $e) {
            $checker = new ModelDbChecker($this->db);
            $checker->check($model);
            if ($checker->isTableMissing()) {
                if (null != $query) {
                    $this->db->execute($query);
                } else {
                    $this->createTable($model);
                }
            } else if ($checker->isFieldMissing()) {
                throw new ModelException("Table ($tableName) corrupted. Please remove it manually.");
            }
            
            return $this->db->search($model);
        }
    }
}
