<?php
/**
 * Author: Mathe E. Botond
 */

namespace QeyWork\Common;

interface IErrorHandler
{
    public function handle(\Exception $e);
}