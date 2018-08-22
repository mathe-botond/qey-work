<?php
/**
 * User: Dexx
 * Date: 1/14/2015
 * Time: 12:19 PM
 */

namespace QeyWork\View;

use QeyWork\View\Html\IHtmlObject;

interface IContentPostProcessor {
    public function process(IHtmlObject $html);
}