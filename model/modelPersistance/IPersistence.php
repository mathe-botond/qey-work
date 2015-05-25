<?php
namespace qeywork;

/**
 * @author klara
 */
interface IPersistence {
    public function setModel(Model $model);
    public function load($id);
    public function insert();
    public function update();
    public function save();
    public function remove();
}
