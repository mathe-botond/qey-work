<?php
namespace QeyWork\Entities\Fields;

/**
 * @author Dexx
 */
class FieldBuilder {
    public function createStringField($name, $size = 255) {
        $field = new TypedField($name, TypedField::VARCHAR_TYPE);
        $field->setSize($size);
        return $field;
    }
    
    public function createIntField($name) {
        return new TypedField($name, TypedField::INT_TYPE);
    }
    
    public function createTextField($name) {
        return new TypedField($name, TypedField::TEXT_TYPE);
    }
    
    public function createTimestampField($name, $defaultCurrent = true) {
        $field = new TypedField($name, TypedField::TIMESPTAMPT_TYPE);
        if ($defaultCurrent) {
            $field->setDefault(TypedField::DEFAULT_TIMESTAMP);
        }
        return $field;
    }
}
