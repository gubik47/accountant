<?php

namespace App\Service\RequestValidator;

use App\Entity\Bank;
use App\Exception\ApiException;
use App\Repository\BankRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BankRequestValidator extends BaseRequestValidator
{
    private BankRepository $bankRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->bankRepo = $em->getRepository(Bank::class);
    }

    public function validateRequest(Request $request): void
    {
        parent::validateRequest($request);

        if (empty($this->data["name"])) {
            throw new ApiException("Missing name.", Response::HTTP_BAD_REQUEST);
        }

        if (empty($this->data["code"])) {
            throw new ApiException("Missing code.", Response::HTTP_BAD_REQUEST);
        }

        if (!is_string($this->data["name"])) {
            throw new ApiException("name has to be string.", Response::HTTP_BAD_REQUEST);
        }

        if (!is_numeric($this->data["code"])) {
            throw new ApiException("code has to be numeric.", Response::HTTP_BAD_REQUEST);
        }

        if (!$request->attributes->get("id") && $this->bankRepo->bankAlreadyExists($this->data["name"])) {
            throw new ApiException("Bank named {$this->data["name"]} already exists.", Response::HTTP_BAD_REQUEST);
        }
    }
}