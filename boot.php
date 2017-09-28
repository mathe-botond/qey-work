<?php
namespace QeyWork;

define('DS', DIRECTORY_SEPARATOR);

$commonDir = __DIR__ . DS . 'Common' . DS;
require $commonDir . 'common.php';
require $commonDir . 'Exceptions.php';
require __DIR__ . DS . 'tools' . DS . 'utils.php';
