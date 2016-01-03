<?php
namespace qeywork;

/**
 * @author Dexx
 */
class EntityDbChecker {
    const CHECK_TABLE = 'SHOW TABLES LIKE :table_name';
    const LIST_COLUMN = 'SHOW COLUMNS FROM :table_name';
    const EXCEPTION = 'Call EntityDbChecker::check first';
    protected $db;
    
    protected $table;
    protected $fields;
    
    public function __construct(DB $db) {
        $this->db = $db;
        
        $this->table = null;
        $this->fields = null;
    }
    
    public function isTableMissing() {
        if ($this->table === null) {
            throw new \BadMethodCallException(self::EXCEPTION);
        }
        return $this->table;
    }
    
    public function isFieldMissing() {
        if ($this->fields == null) {
            throw new \BadMethodCallException(self::EXCEPTION);
        }
        
        $return = false;
        foreach ($this->fields as $result) {
            $return = $return || $result;
        }
        
        return $return;
    }
    
    public function check(Entity $entity) {
        if ($entity->getPersistenceData()->getPersistannceName() != DB::PERSISTANCE_NAME) {
            throw new EntityException('Entity persistance is not of a relational database');
        }
        
        $tableName = $entity->getPersistenceData()->getNameOfPersistenceObject();
        
        $params = array('table_name' => $tableName);
        $tableResult = $this->db->query(self::CHECK_TABLE, $params);
        
        $this->table = empty($tableResult);
        
        $this->fields = array();
        if ($this->table == false) {
            $query = str_replace(':table_name', $tableName, self::LIST_COLUMN);
            $rawColumns = $this->db->query($query);
            $columns = array();
            foreach ($rawColumns as $value) {
                $columns[$value['Field']] = true;
            }
            
            foreach ($entity as $field) {
                if ($field instanceof Field) {
                    $this->fields[$field->getName()] = ! array_key_exists($field->getName(), $columns) ;
                }
            }
        }
    }
}
