<?php
namespace QeyWork\Entities\Persistence;

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
