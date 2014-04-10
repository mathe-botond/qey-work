<?php
namespace qeywork;

/**
 * @author Dexx
 */
interface ValueFilter {
    public function getName();
    public function execute($value);
}
