<?php

namespace App\Controller\Api;

use App\Entity\Bank;
use App\Entity\BankAccount;
use App\Entity\User;
use App\Repository\BankAccountRepository;
use App\Service\RequestValidator\BankAccountRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/accounts", name: "accounts_")]
class BankAccountController extends BaseController
{
    private BankAccountRepository $accountRepo;
    private BankAccountRequestValidator $validator;

    public function __construct(EntityManagerInterface $em, BankAccountRequestValidator $validator)
    {
        parent::__construct($em);

        $this->accountRepo = $em->getRepository(BankAccount::class);
        $this->validator = $validator;
    }

    #[Route("", name: "list", methods: ["GET"])]
    public function list(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $accounts = $this->accountRepo->findBy(["user" => $request->query->get("user")]);

        return $this->json($accounts);
    }

    #[Route("/{id}", name: "get", requirements: ["id" => "\d+"], methods: ["GET"])]
    public function get(int $id): JsonResponse
    {
        $account = $this->accountRepo->find($id);
        if (!$account) {
            throw new NotFoundHttpException("Account ID $id was not found");
        }

        return $this->json($account);
    }

    #[Route("", name: "create", methods: ["PUT"])]
    public function create(Request $request): JsonResponse
    {
        $this->validator->validateRequest($request);

        $account = new BankAccount();

        $data = json_decode($request->getContent(), true);

        $bank = $this->em->find(Bank::class, $data["bank"]);
        $user = $this->em->find(User::class, $data["user"]);

        $account->updateProperties($this->em, $data)
            ->setBank($bank)
            ->setUser($user);

        $this->em->persist($account);
        $this->em->flush();

        return $this->json($account);
    }

    #[Route("/{id}", name: "update", requirements: ["id" => "\d+"], methods: ["POST"])]
    public function update(int $id, Request $request): JsonResponse
    {
        $this->validator->validateRequest($request);

        $account = $this->accountRepo->find($id);
        if (!$account) {
            throw new NotFoundHttpException("Account ID $id was not found");
        }

        $account->updateProperties($this->em, json_decode($request->getContent(), true));

        $this->em->persist($account);
        $this->em->flush();

        return $this->json($account);
    }

    #[Route("/{id}", name: "delete", requirements: ["id" => "\d+"], methods: ["DELETE"])]
    public function delete(int $id): JsonResponse
    {
        $account = $this->accountRepo->find($id);
        if (!$account) {
            throw new NotFoundHttpException("Account ID $id was not found");
        }

        $this->em->remove($account);
        $this->em->flush();

        return $this->apiResponseFactory->createSuccessResponseMessage("Account ID $id was successfully deleted.");
    }
}