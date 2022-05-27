<?php

declare(strict_types=1);

namespace App\Src\Classes;

class CustomFunctions
{
    /**
     * set HTTP response code and exit script with given message
     */
    public static function displayError($message = 'Something went wrong 😥', int $code = 404, array $allow = []): void
    {
        (isset($allow)) && static::setAllowHeader($allow);

        http_response_code($code);
        echo json_encode([
            "errors" => $message
        ]);
        exit;
    }

    public static function outputJson($data = 'No data'): void
    {
        http_response_code(201);
        echo json_encode([
            "data" => $data
        ]);
        exit;
    }

    public static function setAllowHeader($allow)
    {
        if (isset($allow)) {
            $allow_str = implode(", ", $allow);
            header("Allow: $allow_str");
        }
    }

    public static function getHttpInput()
    {
        $data = file_get_contents("php://input");

        return (array) json_decode($data, true);
    }
}