<?php
namespace qeywork;

//removing www
if (strstr($_SERVER['HTTP_HOST'],'www.'))
{
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: http://'.substr($_SERVER['HTTP_HOST'],4).$_SERVER['REQUEST_URI']);
}

require dirname(__FILE__).'/common/common.php';
require dirname(__FILE__).'/common/Exceptions.php';
require dirname(__FILE__).'/Autoloader.php';

require dirname(__FILE__).'/tools/utils.php';

Autoloader::createInstance(__NAMESPACE__, dirname(__FILE__), 'qeyWork');
?>