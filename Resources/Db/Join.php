<?php
/**
 * Author: Mathe E. Botond
 */

namespace QeyWork\Resources\Db;

use QeyWork\Entities\Fields\ReferenceField;

class Join {
    const INNER = 'inner';
    const LEFT = 'left';
    const RIGHT = 'right';
    const OUTER = 'outer';

    /** @var ReferenceField */
    private $field;

    /** @var string */
    private $type;

    function __construct(ReferenceField $field, $type = self::INNER) {
        $this->field = $field;
        $this->type = $type;
    }

    /**
     * @return ReferenceField
     */
    public function getField() {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }
}
