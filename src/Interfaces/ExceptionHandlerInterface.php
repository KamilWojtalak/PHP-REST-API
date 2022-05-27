<?php

declare(strict_types=1);

namespace App\Src\Interfaces;

interface ExceptionHandlerInterface
{

    /** This method handles errors */
    public static function handle_error(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline
    );

    /** This method handles exceptions and outputs it in json format */
    public static function handle_exception(\Throwable $ex);
}
