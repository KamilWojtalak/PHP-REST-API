<?php

declare(strict_types=1);

namespace App\Src\Classes;

use App\Src\Interfaces\ControllerInterface;

class Controller implements ControllerInterface
{
    protected $_gateway;

    public function __construct(
        $gateway
    ) {
        $this->_gateway = $gateway;
    }

    public function processRequest(string $method = 'GET', string $resource, $urlAfterResource): void
    {
        /** You should override that method */
    }
}
