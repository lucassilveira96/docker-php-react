<?php

namespace App\Helpers;

class Response 
{
    public const HTTP_OK = 200;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_CREATED = 201;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    public static function json(int $responseHttpCode, ?array $data): void
    {
        header('Content-Type: application/json');
  
        http_response_code($responseHttpCode);
        echo json_encode($data);
    }
}