<?php
namespace qeywork;

/**
 * @author Dexx
 */
class EntityNullPersistenceData implements IPersistentData {
    public function getNameOfPersistenceObject() {
        return null;
    }

    public function getPersistannceName() {
        return null;
    }
}
