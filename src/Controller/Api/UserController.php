<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\RequestValidator\UserRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/users", name: "users_")]
class UserController extends BaseController
{
    private UserRepository $userRepo;
    private UserRequestValidator $validator;

    public function __construct(EntityManagerInterface $em, UserRequestValidator $validator)
    {
        parent::__construct($em);

        $this->userRepo = $this->em->getRepository(User::class);
        $this->validator = $validator;
    }

    #[Route("", name: "list", methods: ["GET"])]
    public function list(): JsonResponse
    {
        return $this->json($this->userRepo->findAll());
    }

    #[Route("/{id}", name: "get", requirements: ["id" => "\d+"], methods: ["GET"])]
    public function get(int $id): JsonResponse
    {
        $user = $this->userRepo->find($id);
        if (!$user) {
            throw new NotFoundHttpException("User ID $id was not found");
        }

        return $this->json($user);
    }

    #[Route("/{id}", name: "delete", requirements: ["id" => "\d+"], methods: ["DELETE"])]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepo->find($id);
        if (!$user) {
            throw new NotFoundHttpException("User ID $id was not found");
        }

        $this->em->remove($user);
        $this->em->flush();

        return $this->apiResponseFactory->createSuccessResponseMessage("User ID $id was successfully deleted.");
    }

    #[Route("/{id}", name: "update", requirements: ["id" => "\d+"], methods: ["POST"])]
    public function update(int $id, Request $request): JsonResponse
    {
        $this->validator->validateRequest($request);

        $user = $this->userRepo->find($id);
        if (!$user) {
            throw new NotFoundHttpException("User ID $id was not found");
        }

        $user->updateProperties($this->em, json_decode($request->getContent(), true));

        $this->em->persist($user);
        $this->em->flush();

        return $this->json($user);
    }

    #[Route("", name: "create", methods: ["PUT"])]
    public function create(Request $request): JsonResponse
    {
        $this->validator->validateRequest($request);

        $user = new User();

        $user->updateProperties($this->em, json_decode($request->getContent(), true));

        $this->em->persist($user);
        $this->em->flush();

        return $this->json($user);
    }
}