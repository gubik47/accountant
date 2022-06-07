<?php

namespace App\Component;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    const STATUS_SUCCESS = "success";
    const STATUS_ERROR = "error";
}