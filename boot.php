<?php
namespace qeywork;

define('DS', DIRECTORY_SEPARATOR);

$commonDir = __DIR__ . DS . 'common' . DS;
require $commonDir . 'common.php';
require $commonDir . 'Exceptions.php';

$autoloaderDir = $commonDir . 'autoloader' . DS;
require $autoloaderDir . 'PhpNamespaceParser.php';
require $autoloaderDir . 'Autoloader.php';

$classConstantDir = $autoloaderDir . DS . 'classConstantCompatibility' . DS; 
require $classConstantDir . 'ClassConstantCompatibilityCompiler.php';
require $classConstantDir . 'ClassConstantCompilerAutoloader.php';

require __DIR__ . DS . 'tools' . DS . 'utils.php';

$qeyWorkAutoloader = new ClassConstantCompilerAutoloader(__DIR__, 'qeyWork');
