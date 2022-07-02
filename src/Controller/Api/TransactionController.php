<?php

namespace App\Controller\Api;

use App\Entity\BankAccount;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Service\Parser\TransactionImporter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/transactions", name: "transactions_")]
class TransactionController extends BaseController
{
    private TransactionRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);

        $this->repo = $this->em->getRepository(Transaction::class);
    }

    #[Route("", name: "list", methods: ["GET"])]
    public function list(Request $request): JsonResponse
    {
        $accountId = intval($request->query->get("account"));

        $account = $this->em->find(BankAccount::class, $accountId);
        if (!$account) {
            throw new NotFoundHttpException("Account ID $accountId not found.");
        }

        list ($transactions, $totalCount, $pagination) = $this->repo->getTransactionList($account, $request);

        return $this->json([
            "transactions" => $transactions,
            "total_count" => $totalCount,
            "pagination" => $pagination
        ]);
    }

    #[Route("/add", name: "upload_file", methods: ["POST"])]
    public function upload(Request $request, TransactionImporter $importer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $accountId = $data["accountId"];

        $account = $this->em->find(BankAccount::class, $accountId);
        if (!$account) {
            throw new NotFoundHttpException("Account ID $accountId was not found");
        }

        $csvData = base64_decode($data["file"]);

        $count = $importer->importTransactions($account, $csvData);

        return $this->apiResponseFactory
            ->createSuccessResponseMessage("Successfully imported $count transactions for account {$account->getNumber()}");
    }
}