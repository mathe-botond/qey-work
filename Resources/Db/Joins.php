<?php
/**
 * Author: Mathe E. Botond
 */

namespace QeyWork\Resources\Db;

use QeyWork\Entities\Fields\ReferenceField;

class Joins {
    private $joins = [];

    function __construct() {
    }

    public function add(Join $join) {
        $this->joins[] = $join;
    }

    static function single(ReferenceField $field, $type = Join::INNER) {
        $joins = new Joins();
        $joins->add(new Join($field, $type));
        return $joins;
    }
}