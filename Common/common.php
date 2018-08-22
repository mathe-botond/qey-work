<?php
namespace QeyWork\Common;
use Exception;

/**
 * Raplace a single HTML template (e.g. "context {template-name} more context") in the target
 * @param string $label Label names to be replaced
 * @param string $replace Content to be replaced with
 * @param string $target Target content
 * @return string Modified target
 */
function processTemplate($label, $replace, $target)
{
    return str_replace('{'.$label.'}', $replace, $target);
}

/**
 * Raplace a list of HTML template (e.g. "context {template-name} more context") in the target
 * @param array $labels An associative array where the keys are the labels, and the array items are the strings to use when replacing
 * @param array $target Target content
 * @return string Modified target
 */
function processTemplates($labels, $target)
{
    foreach ($labels as $label => $replace)
        $target = str_replace('{'.$label.'}', $replace, $target);
    return $target;
}

/**
 * Replaces all {path} labels in the given HTML source with the server name
 * @param string $html Source code to be patched
 * @return string Patched source
 */
function fixPath($html)
{
    global $config;
    $path = $config['home'];
    return processTemplate("path", $path, $html);
}

/**
 * Redirect to another page
 * @param string $target Target URL
 */
function redirect($target)
{
    if (strpos($target, "\n") !== false || strpos($target, "\r") !== false) {
        throw new ResourceException("Redirect faled: Target contains new lines: '$target'");
    }
    
    header("Location: " . $target);
}

/**
 * @deprecated use Redirect::goHome() instead
 */
function goHome()
{
    global $config;
    redirect($config["home"]);
}

function qey_get_called_class($bt = false,$l = 1) {
    if (!$bt) $bt = debug_backtrace();
    if (!isset($bt[$l])) throw new Exception("Cannot find called class -> stack level too deep.");
    if (!isset($bt[$l]['type'])) {
        throw new Exception ('type not set');
    }
    else switch ($bt[$l]['type']) {
        case '::':
            $lines = file($bt[$l]['file']);
            if ($bt[$l]['line'] > count($lines))
                throw new Exception ("Cant find line the call is made from. Make sure, the class uses UNIX or WINDOWS line endings in file ". $bt[$l]['file']);
            
            $i = 0;
            $callerLine = '';
            do {
                $i++;
                $callerLine = $lines[$bt[$l]['line']-$i] . $callerLine;
            } while (stripos($callerLine,$bt[$l]['function']) === false);
            preg_match('/([a-zA-Z0-9\_]+)::'.$bt[$l]['function'].'/',
                        $callerLine,
                        $matches);
            if (!isset($matches[1])) {
                // must be an edge case.
                throw new Exception ("Could not find caller class: originating method call is obscured.");
            }
            switch ($matches[1]) {
                case 'self':
                case 'parent':
                    return get_called_class($bt,$l+1);
                default:
                    return $matches[1];
            }
            // won't get here.
        case '->': switch ($bt[$l]['function']) {
                case '__get':
                    // edge case -> get class of calling object
                    if (!is_object($bt[$l]['object'])) throw new Exception ("Edge case fail. __get called on non object.");
                    return get_class($bt[$l]['object']);
                default: return $bt[$l]['class'];
            }

        default: throw new Exception ("Unknown backtrace method type");
    }
}

function convertTimestampToAgo($tm)
{
    if (is_string($tm))
        $tm = strtotime($tm);
    
    $cur_tm = time();

    $dif = $cur_tm-$tm;

    $pds = array('second','minute','hour','day','week','month','year','decade');

    $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);

    for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);

    $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
    return $x;
}

function getGravatarUrl($email)
{
    return "http://www.gravatar.com/avatar/" . md5(strtolower(trim($email)));
}

function getJsonLastErrorString()
{
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            return ' - No errors';
        case JSON_ERROR_DEPTH:
            return ' - Maximum stack depth exceeded';
        case JSON_ERROR_STATE_MISMATCH:
            return ' - Underflow or the modes mismatch';
        case JSON_ERROR_CTRL_CHAR:
            return ' - Unexpected control character found';
        case JSON_ERROR_SYNTAX:
            return ' - Syntax error, malformed JSON';
        case JSON_ERROR_UTF8:
            return ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        default:
            return ' - Unknown error';
    }
}

/**
 * Tests if a text starts with an given string.
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
function startsWith($haystack, $needle){
    return strpos($haystack, $needle) === 0;
}

/**
 * Tests if a text ends with a given string.
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

/**
 * Creates random md5 hash
 * @return string generated hash
 */
function createRandomHash() {
    return md5(uniqid(rand(), true));
}

/** special get_class to support entity proxies **/
function getEntityClass($entity) {
    if (is_object($entity) && is_callable(array($entity, '__tell_class'))) {
        return $entity->__tell_class();
    } else {
        return get_class($entity);
    }
}