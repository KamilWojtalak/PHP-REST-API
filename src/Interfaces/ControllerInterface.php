<?php

namespace App\Src\Interfaces;

interface ControllerInterface
{
    public function __construct(
        $gateway
    );

    public function processRequest(string $method = 'GET', string $resource, $urlAfterResource): void;
}