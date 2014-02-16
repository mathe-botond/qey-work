<?php
namespace qeywork;

function redirectToErrorPage($base, $errorDoc, $code, $message) {
    redirect($base->addDirs(array($errorDoc, $code))->field('msg', $message));
}
?>