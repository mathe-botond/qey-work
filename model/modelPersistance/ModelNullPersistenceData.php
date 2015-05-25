<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ModelNullPersistenceData implements IPersistentData {
    public function getNameOfPersistenceObject() {
        return null;
    }

    public function getPersistannceName() {
        return null;
    }
}
