<?php
namespace QeyWork\Entities\Persistence;
use QeyWork\Common\EntityException;
use QeyWork\Entities\Entity;
use QeyWork\Entities\EntityList;
use QeyWork\Resources\Db\DB;

/**
 * @author Dexx
 */
class EntityListDbPersistence {
    /** @var DB */
    private $db;
    
    protected $index = 'id';
            
    public function __construct(DB $db) {
        $this->db = $db;
    }
    
    protected function getTableName(Entity $type) {
        return $type->getPersistenceData()->getNameOfPersistenceObject();
    }
    
    public function insertAll(EntityList $list) {
        if ($list->count() == 0) {
            return ;
        }
        
        $type = $list->getEntityType();
        
        foreach ($type->getFields() as $field) {               
            $key = $field->getName();
            $sqlFieldList[] = "`$key`";
        }
        $sqlFields = join($sqlFieldList, ", ");
        
        $params = array();
        $valueList = array();
        
        foreach ($list as $entity) {
            $valueVector = array();
            
            foreach ($entity->getFields() as $field) {
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
        } catch(\Exception $e) {
            throw new EntityException('Database insert failed: '.$e->getMessage());
        }
    }
}
