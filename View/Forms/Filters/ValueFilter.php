<?php
namespace QeyWork\View\Forms\Filters;

/**
 * @author Dexx
 */
interface ValueFilter {
    public function getName();
    public function execute($value);
}
