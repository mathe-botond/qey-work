<?php
namespace qeywork;

/**
 * @author klara
 */
interface IPersistence {
    public function setEntity(Entity $entity);
    public function load($id);
    public function insert();
    public function update();
    public function save();
    public function remove();
}
