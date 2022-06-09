<?php

namespace App\Controller\Api;

use App\Entity\Bank;
use App\Repository\BankRepository;
use App\Service\RequestValidator\BankRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/banks", name: "banks_")]
class BankController extends BaseController
{
    private BankRepository $bankRepo;
    private BankRequestValidator $validator;

    public function __construct(EntityManagerInterface $em, BankRequestValidator $validator)
    {
        parent::__construct($em);

        $this->bankRepo = $this->em->getRepository(Bank::class);
        $this->validator = $validator;
    }

    #[Route("", name: "list", methods: ["GET"])]
    public function list(): JsonResponse
    {
        return $this->json($this->bankRepo->findAll());
    }

    #[Route("/{id}", name: "get", requirements: ["id" => "\d+"], methods: ["GET"])]
    public function get(int $id): JsonResponse
    {
        $bank = $this->bankRepo->find($id);
        if (!$bank) {
            throw new NotFoundHttpException("Bank ID $id was not found");
        }

        return $this->json($bank);
    }

    #[Route("", name: "create", methods: ["PUT"])]
    public function create(Request $request): JsonResponse
    {
        $this->validator->validateRequest($request);

        $bank = new Bank();

        $bank->updateProperties($this->em, json_decode($request->getContent(), true));

        $this->em->persist($bank);
        $this->em->flush();

        return $this->json($bank);
    }

    #[Route("/{id}", name: "update", requirements: ["id" => "\d+"], methods: ["POST"])]
    public function update(int $id, Request $request): JsonResponse
    {
        $this->validator->validateRequest($request);

        $bank = $this->bankRepo->find($id);
        if (!$bank) {
            throw new NotFoundHttpException("Bank ID $id was not found");
        }

        $bank->updateProperties($this->em, json_decode($request->getContent(), true));

        $this->em->persist($bank);
        $this->em->flush();

        return $this->json($bank);
    }

    #[Route("/{id}", name: "delete", requirements: ["id" => "\d+"], methods: ["DELETE"])]
    public function delete(int $id): JsonResponse
    {
        $bank = $this->bankRepo->find($id);
        if (!$bank) {
            throw new NotFoundHttpException("Bank ID $id was not found");
        }

        $this->em->remove($bank);
        $this->em->flush();

        return $this->apiResponseFactory->createSuccessResponseMessage("Bank ID $id was successfully deleted.");
    }
}