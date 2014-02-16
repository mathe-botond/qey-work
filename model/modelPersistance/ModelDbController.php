<?php
namespace qeywork;

/**
 * Class representing a database table entry and its common operations
 * @author Tenkes Attila, modified by lil-Dexx
 */    
class ModelDbController extends ModelListablePersistentController
{
    protected $id;
    protected $db;
    /** @var Model $model */
    protected $model;
    protected $tableName;
    
    public function __construct(Model $model, DB $db) {
        $this->db = $db;
        $this->model = $model;
        if ($model->persistanceData !== null) {
            $this->tableName = $model->persistanceData->getNameOfPersistanceObject();
        } else {
            throw new BadMethodCallException('Attribute (Model $model) doesn\'t have persistanceData');
        }
    }
    
    /**
     * Returns the name of the table
     * @return The table's name
     */
    public function getTableName() {
        return $this->tableName;
    }
    
    /**
     * Id of this entry. Value is valid only after inserting or loading from table
     * @return int Id of this entry
     */
    public function getModelId() {
        return $this->model->getId();
    }
    
    /**
     * Load entry from database
     * @param int $id ID of the entry
     */
    function load($id)
    {
        $this->model->setId($id);
        $params = array("id" => $id);

        try {
            $result = $this->db->query("select * from ". $this->getTableName()
                    . " where id = :id", $params);
            if (count($result) !== 1) {
                throw new ModelException('DbModel load failed: $id not found in table');
            }

            $row = $result[0];
            unset($row["id"]);
            foreach ($row as $key => $value) {
                if (! property_exists($this->model, $key) || ! $this->model->$key instanceof Field)
                    throw new ModelException("dbLoad failed: Table returned the '$key' " .
                            "undefined in the model");
                $this->model->$key->setValue( $value );
            }
        } catch(Exception $e) {
            throw new ModelException('Database load failed: '.$e->getMessage());
        }
        
        return $this->model;
    }
    
    public function insert()
    {
        $params = array();
        
        foreach($this->model as $key => $field) {
            if (! $field instanceof Field ) {
                continue;
            }
            
            $flds[] = "`" . $field->getName() . "`";
            $value = $field->value();
            if ($value instanceof DbExpression) {
                $vls[] = $value;
            } else {
                $vls[] = ':' . $field->getName();
                $params[$key] = $value;
            }
        }

        $fields = join($flds, ", ");
        $values = join($vls, ", ");

        try {
            $this->db->execute("insert into " . $this->getTableName() 
                    . " ($fields) values ($values)", $params);
            $id = $this->db->lastId();
            $this->model->setId($id);
            return $id;
        } catch(Exception $e) {
            throw new ModelException('Database insert failed: '.$e->getMessage());
        }
    }

    public function update()
    {
        $params = array();
        foreach($this->model as $field) {
            if ($field instanceof Field) {
                $key = $field->getName();
                $sts[] = '`' . $key . '` = :' . $key;
                $params[$key] = $field->value();
            }
        }
		
        $sets = join($sts, ", ");
        try {
            $this->db->execute("update " . $this->getTableName() . " set $sets where id = " 
                    . $this->model->getId(), $params);
        } catch(Exception $e) {
            throw new ModelException('Database update failed: ' . $e->getMessage());
        }
        return $this->model;
    }
    
    public function remove()
    {   
        try {
            $params = array("id" => $this->model->getId());
            $this->db->execute("delete from ". $this->getTableName(). " where id = :id", $params);
            $this->model->setId(NULL);
        } catch (Exception $e) {
            throw new ModelException("Database delete failed: table: " . $this->getTableName()
                    . " id: " . $this->Id . ";  " . $e->getMessage());
        }
    }
}

?>