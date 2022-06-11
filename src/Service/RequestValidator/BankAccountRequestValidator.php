<?php

namespace App\Service\RequestValidator;

use App\Entity\Bank;
use App\Entity\BankAccount;
use App\Entity\User;
use App\Exception\ApiException;
use App\Repository\BankAccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BankAccountRequestValidator extends BaseRequestValidator
{
    private BankAccountRepository $accountRepo;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->accountRepo = $em->getRepository(BankAccount::class);
    }

    public function validateRequest(Request $request): void
    {
        parent::validateRequest($request);

        if (!$request->attributes->get("id")) {
            if (empty($this->data["bank"])) {
                throw new ApiException("Missing bank ID.", Response::HTTP_BAD_REQUEST);
            }

            if (empty($this->data["user"])) {
                throw new ApiException("Missing user ID.", Response::HTTP_BAD_REQUEST);
            }

            $bank = $this->em->find(Bank::class, $this->data["bank"]);
            if (!$bank) {
                throw new ApiException("Bank ID {$this->data["bank"]} not found.", Response::HTTP_BAD_REQUEST);
            }

            $user = $this->em->find(User::class, $this->data["user"]);
            if (!$user) {
                throw new ApiException("User ID {$this->data["user"]} not found.", Response::HTTP_BAD_REQUEST);
            }

            if ($this->accountRepo->accountAlreadyExistsForUser($user, $this->data["number"])) {
                throw new ApiException("Account number {$this->data["number"]} already exists.", Response::HTTP_BAD_REQUEST);
            }
        }

        if (empty($this->data["name"])) {
            throw new ApiException("Missing name.", Response::HTTP_BAD_REQUEST);
        }

        if (empty($this->data["number"])) {
            throw new ApiException("Missing number.", Response::HTTP_BAD_REQUEST);
        }

        if (empty($this->data["owner"])) {
            throw new ApiException("Missing owner.", Response::HTTP_BAD_REQUEST);
        }

        if (!is_string($this->data["name"])) {
            throw new ApiException("name has to be string.", Response::HTTP_BAD_REQUEST);
        }

        if (!is_string($this->data["owner"])) {
            throw new ApiException("owner has to be string.", Response::HTTP_BAD_REQUEST);
        }

        if (!is_string($this->data["number"])) {
            throw new ApiException("number has to be string.", Response::HTTP_BAD_REQUEST);
        }
    }
}