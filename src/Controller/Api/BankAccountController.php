<?php

namespace App\Controller\Api;

use App\Entity\BankAccount;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BankAccountController extends AbstractController
{
    #[Route("/api/accounts", name: "api_account_list", methods: ["GET"])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $repo = $em->getRepository(BankAccount::class);

        $accounts = $repo->findAll();

        $response = [];
        foreach ($accounts as $account) {
            $accountData = [
                "id" => $account->getId(),
                "bank" => [
                    "id" => $account->getBank()->getId(),
                    "name" => $account->getBank()->getName(),
                    "code" => $account->getBank()->getCode()
                ],
                "name" => $account->getName(),
                "number" => $account->getNumber(),
                "owner" => $account->getOwner()
            ];

            $response[] = $accountData;
        }

        return $this->json($response);
    }
}