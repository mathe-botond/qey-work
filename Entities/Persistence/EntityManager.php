<?php
namespace QeyWork\Entities\Persistence;
use QeyWork\Common\DbException;
use QeyWork\Common\EntityException;
use QeyWork\Entities\Entity;
use QeyWork\Entities\Fields\Field;
use QeyWork\Entities\Fields\ReferenceField;
use QeyWork\Resources\Db\DB;

/**
 * Class representing a database table entry and its common operations
 * @author Tenkes Attila, modified by lil-Dexx
 */    
class EntityManager implements IEntityManager
{
    protected $db;
    
    public function __construct(DB $db) {
        $this->db = $db;
    }

    /**
     * Returns the name of the table
     * @param string|Entity $entityType
     * @return string The table's name
     */
    public function getTableName($entityType) {
        $entity = $this->getEntity($entityType);
        return $entity->getPersistenceData()->getNameOfPersistenceObject();
    }

    /**
     * Load entry from database
     * @param int $id ID of the entry
     * @param string|Entity $type
     * @return Entity
     * @throws EntityException
     */
    function load($id, $type)
    {
        $entity = $this->getEntity($type);

        $entity->setId($id);
        $params = array("id" => $id);

        try {
            $result = $this->db->query("select * from ". $this->getTableName($entity)
                    . " where id = :id", $params);
            if (count($result) !== 1) {
                throw new EntityException('DbEntity load failed: $id not found in table');
            }

            $row = $result[0];
            unset($row["id"]);
            $fields = $entity->getFields();
            foreach ($row as $key => $value) {
                if (!array_key_exists($key, $fields) || !$fields[$key] instanceof Field) {
                    //throw new EntityException("dbLoad failed: Table returned the '$key' " .
                    //"undefined in the entity");
                    continue ;
                }
                $fields[$key]->setValue( $value );
            }

            return $entity;
        } catch(\Exception $e) {
            throw new EntityException('Database load failed: '.$e->getMessage());
        }
    }
    
    public function loadReferences(Entity $entity) {
        foreach ($entity->getFields() as $field) {
            if ($field instanceof ReferenceField && intval($field->value()) != 0) {
                $referencedEntity = $this->getEntity($field->getEntityType());
                $this->load($field->value(), $referencedEntity);
                $field->setEntity($referencedEntity);
            }
        }
    }

    /**
     * @param Entity $entity
     * @throws EntityException
     */
    public function insert(Entity $entity)
    {
        $params = array();
        $fieldCollection = [];
        $valueCollection = [];

        foreach($entity->getFields() as $key => $field) {
            if (! $field instanceof Field ) {
                continue;
            }
            
            $fieldCollection[] = "`" . $field->getName() . "`";
            $value = $field->value();
            if ($value instanceof DbExpression) {
                $valueCollection[] = $value;
            } else {
                $valueCollection[] = ':' . $field->getName();
                $params[$key] = $value;
            }
        }

        $fields = join($fieldCollection, ", ");
        $values = join($valueCollection, ", ");

        try {
            $this->db->execute("insert into " . $this->getTableName($entity)
                    . " ($fields) values ($values)", $params);

            $entity->setId($this->db->lastId());
        } catch(\Exception $e) {
            throw new EntityException('Database insert failed: ' . $e->getMessage());
        }

        return $this->db->lastId();
    }

    /**
     * @param Entity $entity
     * @throws EntityException
     */
    public function update(Entity $entity)
    {
        $params = array();
        $queryAssignmentList = array();
        foreach($entity as $field) {
            if ($field instanceof Field) {
                $key = $field->getName();
                $queryAssignmentList[] = '`' . $key . '` = :' . $key;
                $params[$key] = $field->value();
            }
        }
		
        $queryAssignments = join($queryAssignmentList, ", ");
        try {
            $params["id"] = $entity->getId();
            $this->db->execute("update " . $this->getTableName($entity) . " set $queryAssignments where id = :id", $params);
        } catch(\Exception $e) {
            throw new EntityException('Database update failed: ' . $e->getMessage());
        }

        return $entity->getId();
    }
    
    public function remove(Entity $entity)
    {   
        try {
            $params = array("id" => $entity->getId());
            $this->db->execute("delete from ". $this->getTableName($entity). " where id = :id", $params);
            $entity->setId(null);
        } catch (\Exception $e) {
            throw new EntityException("Database delete failed: table: " . $this->getTableName($entity)
                    . " id: " . $entity->getId() . ";  " . $e->getMessage());
        }
    }
    
    public function getDataOrCreateTable($query, $entityType) {
        $entity = $this->getEntity($entityType);
        $tableName = $this->getTableName($entity);
        try {
            $result = $this->db->search($entity);
            return $result;
        } catch (DbException $e) {
            $checker = new EntityDbChecker($this->db);

            $checker->check($entity);
            if ($checker->isTableMissing()) {
                $this->db->execute($query);
            } else if ($checker->isFieldMissing()) {
                throw new EntityException("Table ($tableName) corrupted. Please remove it manually.");
            }
            
            return $this->db->search($entity);
        }
    }

    public function save(Entity $entity) {
        if ($entity->getId() === null) {
            return $this->insert($entity);
        } else {
            return $this->update($entity);
        }
    }

    /**
     * @param $type
     * @return Entity
     */
    public function getEntity($type) {
        if (is_string($type)) {
            $entity = new $type;
        } else {
            $entity = $type;
        };
        return $entity;
    }
}
