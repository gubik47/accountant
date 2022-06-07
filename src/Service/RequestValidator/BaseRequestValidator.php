<?php

namespace App\Service\RequestValidator;

use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseRequestValidator
{
    protected ?array $data;

    public function validateRequest(Request $request): void
    {
        if ($request->getContentType() !== "json") {
            throw new ApiException("Only application/json is accepted as a Content-Type.", Response::HTTP_BAD_REQUEST);
        }

        if (!$request->getContent()) {
            throw new ApiException("Empty request body.", Response::HTTP_BAD_REQUEST);
        }

        $this->data = json_decode($request->getContent(), true);

        if (!$this->data) {
            throw new ApiException("Unable to parse JSON in the request body.", Response::HTTP_BAD_REQUEST);
        }
    }
}