<?php

namespace App\Service\RequestValidator;

use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRequestValidator extends BaseRequestValidator
{
    public function validateRequest(Request $request): void
    {
        parent::validateRequest($request);

        if (empty($this->data["first_name"])) {
            throw new ApiException("Missing first_name.", Response::HTTP_BAD_REQUEST);
        }

        if (empty($this->data["last_name"])) {
            throw new ApiException("Missing last_name.", Response::HTTP_BAD_REQUEST);
        }

        if (!is_string($this->data["first_name"])) {
            throw new ApiException("first_name has to be string.", Response::HTTP_BAD_REQUEST);
        }

        if (!is_string($this->data["last_name"])) {
            throw new ApiException("last_name has to be string.", Response::HTTP_BAD_REQUEST);
        }
    }
}