<?php

namespace App\Src\Interfaces;

use PDO;

interface GatewayInterface
{
    public function __construct(PDO $dbc);
}