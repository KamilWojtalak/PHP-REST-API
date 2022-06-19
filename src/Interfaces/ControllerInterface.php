<?php

namespace App\Src\Interfaces;

interface ControllerInterface
{
    public function __construct(
        $gateway
    );

    public function processRequest(string $resource, $urlAfterResource, string $method = 'GET'): void;
}