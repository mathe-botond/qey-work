<?php
namespace QeyWork\Resources\Db;
use QeyWork\Entities\Fields\Field;
use QeyWork\Entities\Persistence\DbExpression;

/**
 * @author Dexx
 */
class ConditionList {
    const OR_COND = 'OR';
    const AND_COND = 'AND';

    private $conditions = array();
    private $currentOperand = null;
    private $preparedValues = array();
    private $glue;

    function __construct($glue = self::AND_COND) {
        $this->glue = $glue;
    }

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

    public function addConditionList(ConditionList $conditions) {
        $this->conditions[] = $conditions;
        $this->preparedValues = array_merge($this->preparedValues, $conditions->getValues());
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

    public function greaterOrEqualThen($value) {
        $this->addCondition($this->currentOperand, '>=', $value);
    }

    public function lessorEqualThen($value) {
        $this->addCondition($this->currentOperand, '<=', $value);
    }
    
    public function toString() {
        $conditionList = array();
        
        foreach ($this->conditions as $condition) {
            if ($condition instanceof ConditionList) {
                $conditionList[] = $condition->toString();
            } else {
                $conditionList[] = '`' . $condition[0] . '` ' . $condition[1] . ' ' . $condition[2];
            }
        }
        
        if (! empty($conditionList)) {
            $conditions = implode($conditionList, ' ' . $this->glue . ' ');
            return $conditions;
        }
        
        return '';
    }

    public function isConditionListEmpty() {
        return empty($this->conditions);
    }

    public function getValues() {
        return $this->preparedValues;
    }
}
