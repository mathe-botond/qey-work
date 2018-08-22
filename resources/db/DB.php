<?php
namespace QeyWork\Resources\Db;
use QeyWork\Common\DbException;
use QeyWork\Entities\Entity;
use QeyWork\Entities\EntityList;
use QeyWork\Tools\Logger;

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
     * @param DbConfig $dbConfig
     * @param Logger $logger
     * @throws DbException
     */
    public function __construct(DBConfig $dbConfig, Logger $logger)
    {
        try
        {
            $connectionStr = $dbConfig->protocol
                    . ":" . "host=" . $dbConfig->host
                    . ";dbname=" . $dbConfig->dbName;
            
            $this->db = new \PDO(
                    $connectionStr,
                    $dbConfig->user,
                    $dbConfig->password, 
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
     * @return array|bool|EntityList
     * @throws DbException
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
                    if (is_numeric($key)) {
                        $key += 1;
                    }
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
            
            if ($fetch != null && $fetch instanceof Entity) {
                $raw = $data;
                $data = new EntityList($fetch);
                $type = get_class($fetch);
                foreach ($raw as $row) {
                    $entity = new $type();
                    /* @var $entity Entity */
                    $entity->setId($row['id']);
                    foreach ($entity->getFields() as $field) {
                        $field->setValue($row[$field->getName()]);
                    }
                    $data[ $row['id'] ] = $entity;
                }
            }
            
            return $data;
        } catch (\PDOException $e) {
            throw new DbException('Database query failed: ' . $e->getMessage());
        }
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
     * @return bool
     * @throws DbException
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
     * Usage example:
     * for a desired query of: SELECT * FROM users WHERE username LIKE 'a%' AND permissions IN [0, 1, 2]
     * $db->search('MUser', array('username' => 'a', 'permissions' => array(0, 1, 2)));
     *
     * @param Entity $entity - name of the DbEntity class to which the result is fetched and table name is extracted
     * @param ConditionList $conditions - a list containing the conditions
     * @param string ¦ array $order
     * @param int $orderDir
     * @param int $limit
     *
     * @throws DbException
     * @return EntityList
     */
    public function search(
            Entity $entity,
            ConditionList $conditions = null,
            $order = null,
            $orderDir = DB::ORDER_ASC,
            $limit = null)
    {

        try
        {
            $table = $entity->getPersistenceData()->getNameOfPersistenceObject();
            $valueList = array();
            
            $query = "SELECT * FROM `$table`";
            if ($conditions != null && ! $conditions->isConditionListEmpty()) {
                $query .= ' WHERE ' . $conditions->toString();
                $valueList = $conditions->getValues();
            }
            
            if (! empty($order)) {
                if (is_array($order)) {
                    $order = implode('`, `', $order);
                }
                $orderDir = empty($orderDir) ? 'ASC' : 'DESC';
                $query .= " ORDER BY `$order` " . $orderDir;
            }
            
            if (is_array($limit) && count($limit) == 2) {
                $query .= " LIMIT ?, ?";
                $valueList = array_merge($valueList, $limit);
            }
            
            $result = $this->query($query, $valueList, $entity);
            //var_dump($query, $result);
            return $result;
        
        } catch(\PDOException $e) {
            throw new DbException('Table search failed: ' . $e->getMessage());
        }
    }
    
    public function getUnique (
            Entity $entity,
            ConditionList $conditions = null) {
        
        /* @var $result EntityList */
        $result = $this->search($entity, $conditions);
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
     * Get id of the entity inserted last
     */
    public function lastId()
    {
        return $this->db->lastInsertId();
    }
}
