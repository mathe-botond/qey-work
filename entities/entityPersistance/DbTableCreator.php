<?php
namespace qeywork;

/**
 * @author Dexx
 */
class DbTableCreator {
    private $db;
    
    /** @var Entity */
    protected $entity;
    
    public function __construct(DB $db) {
        $this->db = $db;
    }
    
    protected function checkEntity(Entity $entity) {
        $persistance = $entity->getPersistenceData();
        if ($persistance == null || $persistance->getPersistannceName() != DB::PERSISTANCE_NAME) {
            throw new EntityException('Missing or invalid entity persistance.');
        }
    }
    
    public function createTable(Entity $entity) {
        $this->checkEntity($entity);
        
        $table = $entity->getPersistenceData()->getNameOfPersistenceObject();
        $fieldList[0] = 'id INT NOT NULL AUTO_INCREMENT';
        $keys[0] = 'PRIMARY KEY (id)';
        
        $fields = $entity->getFields();
        foreach ($fields as $field) {
            if ($field instanceof TypedField) {
                $fieldList[] = $field->getTypeString();
            }
            if ($field instanceof ReferenceField) {
                $name = $field->getName();
                $refenrencedTable = $field->getEntityType()
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
    
    public function getDataOrCreateTable(Entity $entity, $query = null) {
        $tableName = $entity->getPersistenceData()->getNameOfPersistenceObject();
        
        try {
            return $this->db->search($entity);
        } catch (DbException $e) {
            $checker = new EntityDbChecker($this->db);
            $checker->check($entity);
            if ($checker->isTableMissing()) {
                if (null != $query) {
                    $this->db->execute($query);
                } else {
                    $this->createTable($entity);
                }
            } else if ($checker->isFieldMissing()) {
                throw new EntityException("Table ($tableName) corrupted. Please remove it manually.");
            }
            
            return $this->db->search($entity);
        }
    }
}
