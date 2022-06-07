<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Generic API exception.
 */
class ApiException extends Exception
{
    public function __construct($message = "", $code = Response::HTTP_INTERNAL_SERVER_ERROR, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}