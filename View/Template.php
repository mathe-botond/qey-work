<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2/27/2015
 * Time: 11:55 AM
 */

namespace QeyWork\View;


class Template {
    private $template;

    public function __construct($raw) {
        $this->template = $raw;
    }

    public function replace($token, $value) {
        $templateKey = '{' . $token . '}';
        $this->template = str_replace($templateKey, $value, $this->template);
    }

    public function getProcessed() {
        return $this->template;
    }
} 