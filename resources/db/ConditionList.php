<?php
namespace qeywork;

/**
 * @author Dexx
 */
class ConditionList {
    private $conditions = array();
    private $currentOperand = null;
    private $preparedValues = array();
    
    public function addCondition(Field $operand, $condition, $value) {
        if ($value === null) {
            $value = 'NULL';
        } else if ($value instanceof DbExpression) {
            $this->preparedValues = array_merge($this->preparedValues, $value->getParams());
            $value = $value->toString();
        } else if (is_array($value)) {
            $this->preparedValues = array_merge($this->preparedValues, $value);
            $value = '(' . implode(',', array_fill(0, count($value), '?')) . ')';
        } else {
            $this->preparedValues[] = $value;
            $value = '?';
        }
        
        $this->conditions[] = array($operand->getName(), $condition, $value);
    }
    
    public function add(Field $operand) {
        $this->currentOperand = $operand;
        return $this;
    }
    
    public function in(array $values) {
        $this->addCondition($this->currentOperand, 'IN', $values);
    }
    
    public function equals($value) {
        if (is_array($value)) {
            
        } else if (strpos($value, "%") !== false) {
            $condition = ' LIKE ';
        } else if ($value === null) {
            $condition = ' IS ';
        } else {
            $condition = ' = ';
        }
        $this->addCondition($this->currentOperand, $condition, $value);
    }
    
    public function greaterThen($value) {
        $this->addCondition($this->currentOperand, '>', $value);
    }
    
    public function lessThen($value) {
        $this->addCondition($this->currentOperand, '<', $value);
    }
    
    public function toString() {
        $conditionList = array();
        
        foreach ($this->conditions as $condition) {
            $conditionList[] = '`' . $condition[0] . '` ' . $condition[1] . ' ' . $condition[2] ;
        }
        
        if (! empty($conditionList)) {
            $conditions = implode($conditionList, ' AND ');
            return " WHERE " . $conditions;
        }
        
        return '';
    }
    
    public function getValues() {
        return $this->preparedValues;
    }
}
