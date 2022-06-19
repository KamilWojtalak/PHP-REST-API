<?php

declare(strict_types=1);

namespace App\Src\Classes;

use App\Src\Interfaces\ControllerInterface;

abstract class Controller implements ControllerInterface
{
    public function __construct(
        protected $_gateway
    ) {}

    abstract public function processRequest(string $resource, $urlAfterResource, string $method = 'GET'): void;
}
