<?php
namespace qeywork;

/**
 * @author dexx
 */
class ValueListProvider {
    private $db;
    private $type;
    private $nameField;
    private $valueField;

    public function __construct(DB $db, Model $type, Field $valueField, Field $nameField = null) {
        $this->db = $db;
        $this->type = $type;
        $this->valueField = $valueField;
        $this->nameField = $nameField;
    }
    
    public function getValueList() {
        $result = $this->db->search($this->type);
        $list = new SmartArrayObject();
        foreach ($result as $entry) {
            /* @var $entry Model */
            $fields = $entry->getFields();
            $value = $fields[ $this->valueField->getName() ]->value();
            
            if ($this->nameField == null) {
                $name = $entry->getId();
            } else {
                $name = $fields[ $this->nameField->getName() ]->value();
            }
            
            $list[$name] = $value;
        }
        return $list;
    }
}
