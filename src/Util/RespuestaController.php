<?php

namespace App\Util;

use Symfony\Component\HttpFoundation\JsonResponse;

class RespuestaController
{
    public static function format(int $statusCode = 200, $respuesta): JsonResponse
    {
        $data = [
            "code" => $statusCode,
            "respuesta" => $respuesta
        ];

        return new JsonResponse($data, $statusCode);
    }
}