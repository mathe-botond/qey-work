<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ModelListDbController {
    /** @var Model */
    protected $type;
    /** @var ModelList */
    protected $result;
    /** @var DB */
    private $db;
    
    protected $index = 'id';
            
    public function __construct(Model $type, DB $db) {
        $this->type = $type;
        $this->db = $db;
        $this->result = new ModelList($type);
    }
    
    /**
     * Filter a given table based on a set of values. This function can construct queries such as:
     *       SELECT * FROM table_name WHERE condition1 AND condition2 AND ... AND conditionN
     *
     * A condition can have an operator of 'LIKE' or 'IN'
     *       array: IN
     *       string: LIKE
     *
     * @param array $conditions - a list containing the conditions
     *
     * Usage example:
     * for a desired query of: SELECT * FROM users WHERE username LIKE 'a%' AND permissions IN [0, 1, 2]
     * $controller->search(array('username' => 'a%', 'permissions' => array(0, 1, 2)));
     */
    public function search(
        $conditions = array(),
        $order = null,
        $orderDir = DB::ORDER_ASC,
        $limit = null)
    {
        try
        {
            $table = $this->type->persistanceData->getNameOfPersistanceObject();
            
            $conditionList = array();
            $valueList = array();
            
            if (!is_array($conditions)) $conditions = array(); 
            foreach ($conditions as $name => $value)
            {
                $condition = $name;
                if (is_array($value)) {
                    $condition .= ' IN ';
                } else if (strpos($value, "%") !== false) {
                    $condition .= ' LIKE ';
                } else if ($value === null) {
                    $condition .= ' IS ';
                } else {
                    $condition .= ' = ';
                }
                
                if (is_array($value)) {
                    $condition .= '(' . implode(',', array_fill(0, count($value), '?')) . ')';
                    $valueList = array_merge($valueList, $value); 
                } else if ($value === null) {
                    $condition .= 'NULL';
                } else {
                    $condition .= '?';
                    array_push($valueList, $value);
                }
                
                $conditionList[] = $condition;
            }
            
            $query = "SELECT * FROM `$table`";
            if (! empty($conditionList)) {
                $conditions = implode($conditionList, ' AND ');
                $query .= " WHERE " . $conditions;
            }
            
            if (! empty($order)) {
                $orderDir = empty($orderDir) ? 'ASC' : 'DESC';
                $query .= " ORDER BY `$order` " . $orderDir;
            }
            
            if (is_array($limit) && count($limit) == 2) {
                $query .= " LIMIT ?, ?";
                $valueList = array_merge($valueList, $limit);
            }
            
            $valueList = array_merge(array(0), array_values($valueList));
            unset($valueList[0]);
            
            $this->result = $this->query($query, $valueList, $this->type);
            //var_dump($query, $result);
        }
        catch(\PDOException $e)
        {
            throw new DbException('Table filtering failed: ' . $e->getMessage() . var_export(debug_backtrace(), true));
        }
        return $this;
    }
    
    public function getUnique (
            Model $model,
            $conditions = array()) {
        
        /* @var $result ModelList */
        $this->result = $this->search($model, $conditions);
        if ($this->result->count() > 1) {
            throw new DbException('Conditions do not provide unique result');
        }
        
        if ($this->result->count() == 0) {
            return null;
        } else {
            return $this->result[0];
        }
    }
    
    public function getAll() {
        return $this->search();
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function indexResult(Field $field) {
        foreach ($this->type as $key => $typeField) {
            if ($typeField->getName() == $field->getName()) {
                $name = $key;
                break;
            }
        }
        
        if ($name == null) {
            $name = $field->getName();
            $type = get_class($this->type);
            throw new ArgumentException("No matching field '$name' in type '$type'");
        }
        
        $this->index = $name;
        $indexedResult = new ModelList($this->type);
        foreach ($this->result as $entry) {
            $indexedResult[ $entry->$name->value() ] = $entry;
        }
        $this->result = $indexedResult;
        return $this;
    }
    
    public function resultIndexExists($index) {
        return $this->result->offsetExists($index);
    }
    
    public function getFromResult($index) {
        if ($this->resultIndexExists($index)) {
            return $this->result[$index];
        } else {
            throw new ArgumentException("Index value '$index' of type '$this->index' not found");
        }
    }
    
    public function getResult() {
        return $this->result;
    }
}

?>
