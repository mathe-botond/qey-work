<?php
namespace qeywork;

/**
 * Class representing a database table entry and its common operations
 * @author Tenkes Attila, modified by lil-Dexx
 */    
class ModelDbPersistence extends ModelListablePersistentController
{
    protected $id;
    protected $db;
    /** @var Model $model */
    protected $model;
    protected $tableName;
    
    public function __construct(DB $db) {
        $this->db = $db;
    }
    
    public function setModel(Model $model) {
        $this->model = $model;
        $this->id = $model->getId();

        $this->tableName = $model->getPersistenceData()->getNameOfPersistenceObject();
    }
    
    /**
     * Returns the name of the table
     * @return The table's name
     */
    public function getTableName() {
        return $this->tableName;
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
            $fields = $this->model->getFields();
            foreach ($row as $key => $value) {
                if (!array_key_exists($key, $fields) || !$fields[$key] instanceof Field) {
                    throw new ModelException("dbLoad failed: Table returned the '$key' " .
                    "undefined in the model");
                }
                $fields[$key]->setValue( $value );
            }
        } catch(Exception $e) {
            throw new ModelException('Database load failed: '.$e->getMessage());
        }
        
        $this->id = $id;
    }
    
    public function loadReferences() {
        foreach ($this->model->getFields() as $field) {
            if ($field instanceof ReferenceField && intval($field->value()) != 0) {
                $controller = new static($field->getModelType(), $this->db);
                $controller->load($field->value());
            }
        }
    }
    
    public function insert()
    {
        $params = array();
        
        foreach($this->model->getFields() as $key => $field) {
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
            $this->id = $this->db->lastId();
            $this->model->setId($this->id);
            return $this->id;
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
        return $this->id;
    }
    
    public function remove()
    {   
        try {
            $params = array("id" => $this->model->getId());
            $this->db->execute("delete from ". $this->getTableName(). " where id = :id", $params);
            $this->model->setId(null);
            $this->id = null;
        } catch (Exception $e) {
            throw new ModelException("Database delete failed: table: " . $this->getTableName()
                    . " id: " . $this->Id . ";  " . $e->getMessage());
        }
    }
    
    public function getDataOrCreateTable($query) {
        $tableName = $this->model->persistanceData->getNameOfPersistenceObject();
        try {
            $result = $this->db->search($this->model);
            return $result;
        } catch (DbException $e) {
            $checker = new ModelDbChecker($this->db);

            $checker->check($this->model);
            if ($checker->isTableMissing()) {
                $this->db->execute($query);
            } else if ($checker->isFieldMissing()) {
                throw new ModelException("Table ($tableName) corrupted. Please remove it manually.");
            }
            
            return $this->db->search($this->model);
        }
    }
    
    public function getModel() {
        return $this->model;
    }
}
