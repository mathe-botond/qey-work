<?php
/**
 * Author: Mathe E. Botond
 */

namespace QeyWork\Common\Actions;

use QeyWork\Common\IAction;
use QeyWork\Entities\ArrayEntityMapper;
use QeyWork\Resources\Request;

abstract class JsonAction implements IAction {
    /** @var Request */
    private $request;

    /** @var ArrayEntityMapper */
    private $mapper;

    public function __construct(Request $request, ArrayEntityMapper $mapper) {
        $this->request = $request;
        $this->mapper = $mapper;
    }

    public abstract function getBodyType();

    public abstract function executeOnBody($event);

    public function execute() {
        $rawBody = file_get_contents('php://input');
        $rawBody = json_decode($rawBody, true);
        $mapped = $this->getBodyType();
        $this->mapper->map($rawBody, $mapped);
        $this->executeOnBody($mapped);
    }
}
