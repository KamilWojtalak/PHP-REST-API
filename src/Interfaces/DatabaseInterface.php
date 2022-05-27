<?php

declare(strict_types=1);

namespace App\Src\Interfaces;

interface DatabaseInterface
{
    public function __construct(
        string $host,
        string $dbname,
        string $user,
        string $pswd
    );

    public function getConnection();
}
