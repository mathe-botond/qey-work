<?php
namespace qeywork;

/**
 * DB handler class
 * @author lil-Dexx, Tenkes Attila
 */
class DB
{
    const PERSISTANCE_NAME = 'db';
    
    const FETCH_ASSOC = 1;
    const ORDER_ASC = 0;
    const ORDER_DESC = 1;
    
    private $db = null;
    private $logger = null;
    
    /**
     * @param DbConfig $dbConfigurator
     * @param Logger $logger
     * @throws DbException
     */
    public function __construct($dbConfigurator, $logger = null)
    {
        try
        {
            $connectionStr = $dbConfigurator->protocol
                    . ":" . "host=" . $dbConfigurator->host
                    . ";dbname=" . $dbConfigurator->dbName;
            
            $this->db = new \PDO(
                    $connectionStr,
                    $dbConfigurator->user,
                    $dbConfigurator->password, 
                    array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
               );
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            $this->logger = $logger;
        }
        catch (\PDOException $e) 
        {
            throw new DbException('Failed to connect to database: ' . $e->getMessage(), $e->getCode());
        }
    }
    
    /**
     * Query the database
     * @param string $query A string containing the query (can have placeholders)
     * @param array $params A string array containing the params to be replaced with the placeholders
     * @param mixed $fetch If a string is given with a class name the result will be fetched
     *         into these types of objects, otherwise an associative array is returned
     * @param int $limit The number of entried to be returned at most
     */
    public function query($query, $params = null, $fetch = null, $limit = -1)
    {
        try {
            $this->logger->debug(__FILE__ ." ::: ". __LINE__ ." ::: ". __CLASS__ ." ::: ". __METHOD__ .":::\t". "DB query: " . $query . " --- Params: " . print_r($params, true));
            
            $stmt = $this->db->prepare($query);
            if ($params == null) {
                $stmt->execute();
            } else {
                foreach ($params as $key => $param) {
                    $stmt->bindValue($key, $param, $this->getConstantByType($param));
                }
                $stmt->execute();
            }
            
            $data = array();
            
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            
            while (($row = $stmt->fetch()) && $limit != 0) {
                $data[] = $row;
                if ($limit > 0) {
                    --$limit;
                }
            }
            
            if ($fetch != null && $fetch instanceof Model) {
                $raw = $data;
                $data = new ModelList($fetch);
                $type = get_class($fetch);
                foreach ($raw as $row) {
                    $model = new $type();
                    /* @var $model Model */
                    $model->setId($row['id']);
                    foreach ($model as $field) {
                        if ($field instanceof Field) {
                            $field->setValue($row[$field->getName()]);
                        }
                    }
                    $data[ $row['id'] ] = $model;
                }
            }
            
            return $data;
        } catch (PDOException $e) {
            throw new DbException('Database query failed: ' . $e->getMessage() . var_export(debug_backtrace(), true));
        }
        return false;
    }
    
    /**
     * Get PDO constant form the value type
     * @param mixed $value
     * @return int PDO constant
     */
    public function getConstantByType($value)
    {
        $type = gettype($value);
        
        if ($type === 'integer' || $type === 'double') {
            return \PDO::PARAM_INT;
        }
        if ($type === 'boolean') {
            return \PDO::PARAM_BOOL;
        }
        if ($type === 'NULL') {
            return \PDO::PARAM_NULL;
        }
        //Default 
        return \PDO::PARAM_STR;
    }
    
    /**
     * Execute mysql command
     * @param string $query
     * @param array $params
     */
    public function execute($query, $params = null)
    {        
        try {
            $this->logger->debug(__FILE__ ." ::: ". __LINE__ ." ::: ". __CLASS__ ." ::: ". __METHOD__ .":::\t". "DB query: " . $query . " --- Params: " . print_r($params, true));
            
            $stmt = $this->db->prepare($query);
            
            if ($params == null) {
                $result = $stmt->execute();
            } else {
                $result = $stmt->execute($params);
            }

            return $result;
        } catch (\PDOException $e) {
            throw new DbException('Database execute command failed: ' . $e->getMessage());
        }
        return false;
    }
    
    public function __call($name, $arguments)
    {
        try {
            return call_user_func_array(array($this->db, $name), $arguments);
        } catch (\PDOException $e) {
            throw new DbException("Database $name command failed: " . $e->getMessage());
        }
    }
    
    /**
     * Filter a given table based on a set of values. This function can construct queries such as:
     *       SELECT * FROM table_name WHERE condition1 AND condition2 AND ... AND conditionN
     *
     * A condition can have an operator of '=' or 'IN'
     *       array: IN
     *       other: =
     *
     * @param string\ModelEntity $model - name of the DbModel class to which the result is fetched and table name is extracted
     * @param array $conditions - a list containing the conditions
     *
     * Usage example:
     * for a desired query of: SELECT * FROM users WHERE username LIKE 'a%' AND permissions IN [0, 1, 2]
     * $db->search('MUser', array('username' => 'a', 'permissions' => array(0, 1, 2)));
     */
    public function search(
            Model $model,
            $conditions = array(),
            $order = null,
            $orderDir = DB::ORDER_ASC,
            $limit = null)
    {
        try
        {
            $table = $model->persistanceData->getNameOfPersistanceObject();
            
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
            
            $result = $this->query($query, $valueList, $model);
            //var_dump($query, $result);
            return $result;
        }
        catch(\PDOException $e)
        {
            throw new DbException('Table filtering failed: ' . $e->getMessage() . var_export(debug_backtrace(), true));
            return false;
        }
    }
    
    public function getUnique (
            Model $model,
            $conditions = array()) {
        
        /* @var $result ModelList */
        $result = $this->search($model, $conditions);
        if ($result->count() > 1) {
            throw new DbException('Conditions do not provide unique result');
        }
        
        if ($result->count() == 0) {
            return null;
        } else {
            return $result->first();
        }
    }
    
    
    /**
     * Get id of the record inserted last
     */
    public function lastId()
    {
        return $this->db->lastInsertId();
    }
    
    public function getModelTableController(Model $model) {
        return new ModelDbTableController($this, $model);
    }
}
