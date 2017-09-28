<?php
namespace QeyWork\Entities\Persistence;

/**
 * @author Dexx
 */
class EntityDbData implements IPersistentData {
    const DB_PERSISTANCE_NAME = 'db';
    protected $name;
    
    public function __construct($tableName) {
        $this->name = $tableName;
    }
    
    public function getNameOfPersistenceObject() {
        return $this->name;
    }

    public function getPersistannceName() {    
        return self::DB_PERSISTANCE_NAME;
    }
}
