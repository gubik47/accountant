<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Generic API exception.
 */
class ApiException extends Exception
{
    public function __construct(string $message = "", int $code = Response::HTTP_INTERNAL_SERVER_ERROR, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}