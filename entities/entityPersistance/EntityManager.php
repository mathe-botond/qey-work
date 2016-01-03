<?php
namespace qeywork;

/**
 * Class representing a database table entry and its common operations
 * @author Tenkes Attila, modified by lil-Dexx
 */    
class EntityManager extends EntityListablePersistentController
{
    protected $id;
    protected $db;
    /** @var Entity $entity */
    protected $entity;
    protected $tableName;
    
    public function __construct(DB $db) {
        $this->db = $db;
    }
    
    public function setEntity(Entity $entity) {
        $this->entity = $entity;
        $this->id = $entity->getId();

        $this->tableName = $entity->getPersistenceData()->getNameOfPersistenceObject();
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
        $this->entity->setId($id);
        $params = array("id" => $id);

        try {
            $result = $this->db->query("select * from ". $this->getTableName()
                    . " where id = :id", $params);
            if (count($result) !== 1) {
                throw new EntityException('DbEntity load failed: $id not found in table');
            }

            $row = $result[0];
            unset($row["id"]);
            $fields = $this->entity->getFields();
            foreach ($row as $key => $value) {
                if (!array_key_exists($key, $fields) || !$fields[$key] instanceof Field) {
                    //throw new EntityException("dbLoad failed: Table returned the '$key' " .
                    //"undefined in the entity");
                    continue ;
                }
                $fields[$key]->setValue( $value );
            }
        } catch(Exception $e) {
            throw new EntityException('Database load failed: '.$e->getMessage());
        }
        
        $this->id = $id;
    }
    
    public function loadReferences() {
        foreach ($this->entity->getFields() as $field) {
            if ($field instanceof ReferenceField && intval($field->value()) != 0) {
                $controller = new static($this->db);
                $controller->setEntity($field->getEntityType());
                $controller->load($field->value());
                $field->setEntity($controller->getEntity());
            }
        }
    }
    
    public function insert()
    {
        $params = array();
        
        foreach($this->entity->getFields() as $key => $field) {
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
            $this->entity->setId($this->id);
            return $this->id;
        } catch(Exception $e) {
            throw new EntityException('Database insert failed: '.$e->getMessage());
        }
    }

    public function update()
    {
        $params = array();
        foreach($this->entity as $field) {
            if ($field instanceof Field) {
                $key = $field->getName();
                $sts[] = '`' . $key . '` = :' . $key;
                $params[$key] = $field->value();
            }
        }
		
        $sets = join($sts, ", ");
        try {
            $this->db->execute("update " . $this->getTableName() . " set $sets where id = " 
                    . $this->entity->getId(), $params);
        } catch(Exception $e) {
            throw new EntityException('Database update failed: ' . $e->getMessage());
        }
        return $this->id;
    }
    
    public function remove()
    {   
        try {
            $params = array("id" => $this->entity->getId());
            $this->db->execute("delete from ". $this->getTableName(). " where id = :id", $params);
            $this->entity->setId(null);
            $this->id = null;
        } catch (Exception $e) {
            throw new EntityException("Database delete failed: table: " . $this->getTableName()
                    . " id: " . $this->Id . ";  " . $e->getMessage());
        }
    }
    
    public function getDataOrCreateTable($query) {
        $tableName = $this->entity->persistanceData->getNameOfPersistenceObject();
        try {
            $result = $this->db->search($this->entity);
            return $result;
        } catch (DbException $e) {
            $checker = new EntityDbChecker($this->db);

            $checker->check($this->entity);
            if ($checker->isTableMissing()) {
                $this->db->execute($query);
            } else if ($checker->isFieldMissing()) {
                throw new EntityException("Table ($tableName) corrupted. Please remove it manually.");
            }
            
            return $this->db->search($this->entity);
        }
    }
    
    public function getEntity() {
        return $this->entity;
    }
}
