<?php

namespace App\Service\Factory;

use App\Component\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseFactory
{
    public function createSuccessResponseMessage(string $message, int $httpStatusCode = Response::HTTP_OK): ApiResponse
    {
        return $this->createApiResponse(ApiResponse::STATUS_SUCCESS, $message, $httpStatusCode);
    }

    public function createErrorResponseMessage(string $message, int $httpStatusCode = Response::HTTP_OK): ApiResponse
    {
        return $this->createApiResponse(ApiResponse::STATUS_ERROR, $message, $httpStatusCode);
    }

    private function createApiResponse(string $status, string $message, int $httpStatusCode): ApiResponse
    {
        return new ApiResponse([
            "status" => $status,
            "message" => $message
        ], $httpStatusCode);
    }
}