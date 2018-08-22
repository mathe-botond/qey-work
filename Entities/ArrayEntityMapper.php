<?php
/**
 * Author: Mathe E. Botond
 */

namespace QeyWork\Entities;

use QeyWork\Entities\Fields\ReferenceField;

class ArrayEntityMapper {
    public function map(array $data, $target) {
        if ($target instanceof Entity) {
            $this->mapEntity($data, $target);
        } else {
            $this->mapPlain($data, $target);
        }
    }

    private function mapEntity($data, Entity $entity) {
        $idField = $entity->getIdField();
        if (array_key_exists($idField->getName(), $data)) {
            $idField->setValue($data[$idField->getName()]);
        }

        foreach ($entity as $key => $field) {
            if (! array_key_exists($key, $data)) {
                continue;
            } else {
                $value = $data[$key];
            }

            if ($field instanceof ReferenceField) {
                if ($field->getEntity(true) != null && is_array($value)) {
                    $this->mapEntity($value, $field->getEntity(true));
                }
            } else {
                $field->setValue($value);
            }
        }
    }

    private function mapPlain($data, $target) {
        foreach ($target as $key => $field) {
            if (! array_key_exists($key, $data)) {
                continue;
            } else {
                $value = $data[$key];
            }

            if (is_object($field)) {
                $this->mapPlain($value, $field);
            } else {
                $target->$key = $value;
            }
        }
    }
}
