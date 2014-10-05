<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ModelListDbPersistence {
    /** @var DB */
    private $db;
    
    protected $index = 'id';
            
    public function __construct(DB $db) {
        $this->db = $db;
    }
    
    protected function getTableName(Model $type) {
        return $type->getPersistenceData()->getNameOfPersistenceObject();
    }
    
    public function insertAll(ModelList $list) {
        if ($list->count() == 0) {
            return ;
        }
        
        $type = $list->getModelType();
        
        foreach ($type->getFields() as $field) {               
            $key = $field->getName();
            $sqlFieldList[] = "`$key`";
        }
        $sqlFields = join($sqlFieldList, ", ");
        
        $params = array();
        $valueList = array();
        
        foreach ($list as $model) {
            $valueVector = array();
            
            foreach ($model->getFields() as $field) {               
                $value = $field->value();
                if ($value instanceof DbExpression) {
                    $valueVector[] = $value;
                } else {
                    $valueVector[] = '?';
                    $params[] = $value;
                }
            }

            $valueList[] = join($valueVector, ", ");
        }
        
        $values = '(' . join($valueList, "), (") . ')';

        try {
            $query = "insert into " . $this->getTableName($type) . " ($sqlFields) values $values";
            $this->db->execute($query, $params);
        } catch(Exception $e) {
            throw new ModelException('Database insert failed: '.$e->getMessage());
        }
    }
}
