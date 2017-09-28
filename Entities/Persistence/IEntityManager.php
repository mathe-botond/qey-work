<?php
namespace QeyWork\Entities\Persistence;
use QeyWork\Common\EntityException;
use QeyWork\Entities\Entity;

/**
 * @author klara
 */
interface IEntityManager {
    /**
     * Load entry from database
     * @param int $id ID of the entry
     * @param string|Entity $type
     */
    function load($id, $type);


    /**
     * @param Entity $entity
     * @return int Id of inserted entity
     * @throws EntityException
     */
    public function insert(Entity $entity);


    /**
     * @param Entity $entity
     * @return int Id of updated entity
     * @throws EntityException
     */
    public function update(Entity $entity);

    public function save(Entity $entity);

    public function remove(Entity $entity);
}
