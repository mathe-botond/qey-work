<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ModelListDbController {
    /** @var ModelList */
    protected $list;
    /** @var DB */
    private $db;
    
    protected $index = 'id';
            
    public function __construct(ModelList $list, DB $db) {
        $this->list = $list;
        $this->db = $db;
    }
    
    protected function getTableName() {
        return $this->list->getModelType()->persistanceData->getNameOfPersistanceObject();
    }
    
    public function insertAll() {
        if ($this->list->count() == 0) {
            return ;
        }
        
        $type = $this->list->getModelType();
        
        foreach ($type->getFields() as $field) {               
            $key = $field->getName();
            $sqlFieldList[] = "`$key`";
        }
        $sqlFields = join($sqlFieldList, ", ");
        
        $params = array();
        $valueList = array();
        
        foreach ($this->list as $model) {
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
            $query = "insert into " . $this->getTableName() . " ($sqlFields) values $values";
            $this->db->execute($query, $params);
        } catch(Exception $e) {
            throw new ModelException('Database insert failed: '.$e->getMessage());
        }
    }
}

?>
