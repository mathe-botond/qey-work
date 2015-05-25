<?php
/**
 * User: Dexx
 * Date: 1/14/2015
 * Time: 12:19 PM
 */

namespace qeywork;

interface IContentPostProcessor {
    public function process(IHtmlObject $html);
}