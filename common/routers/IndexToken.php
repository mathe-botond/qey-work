<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 5/25/2015
 * Time: 10:17 PM
 */

namespace qeywork;

class IndexToken {
    /** @var string */
    private $indexToken;

    public function __construct($indexToken) {
        $this->indexToken = $indexToken;
    }

    public function get() {
        return $this->indexToken;
    }
}