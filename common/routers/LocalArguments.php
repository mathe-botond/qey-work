<?php
namespace QeyWork\Common\Routers;

/**
 * @author Dexx
 */
class LocalArguments extends Arguments {
    public function __construct($token, array $args= array()) {
        $this->token = $token;
        $this->args = $args;
    }
}
