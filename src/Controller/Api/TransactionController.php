<?php

namespace App\Controller\Api;

use App\Entity\BankAccount;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    #[Route("/api/accounts/{id}/transactions", name: "api_transaction_list", methods: ["GET"])]
    public function list(int $id, EntityManagerInterface $em): JsonResponse
    {
        $repo = $em->getRepository(BankAccount::class);

        $account = $repo->find($id);

        $response = [];
        foreach ($account->getTransactions() as $transaction) {
            $response[] = $this->addTransaction($transaction);
        }

        return $this->json($response);
    }

    private function addTransaction(Transaction $transaction): array
    {
        $json = [
            "id" => intval($transaction->getId()),
            "transaction_id" => strval($transaction->getTransactionId()),
            "type" => strval($transaction->getType()),
            "amount" => $transaction->getAmount(),
            "currency" => strval($transaction->getCurrency())
        ];

        if ($transaction->getDateOfIssue()) {
            $json["date_of_issue"] = $transaction->getDateOfIssue()->format("Y-m-d");
        }

        if ($transaction->getDateOfCharge()) {
            $json["date_of_charge"] = $transaction->getDateOfCharge()->format("Y-m-d");
        }

        if ($transaction->getDescription()) {
            $json["description"] = $transaction->getDescription();
        }

        if ($transaction->getNote()) {
            $json["note"] = $transaction->getNote();
        }

        if ($transaction->getVariableSymbol()) {
            $json["variable_symbol"] = $transaction->getVariableSymbol();
        }

        if ($transaction->getConstantSymbol()) {
            $json["constant_symbol"] = $transaction->getConstantSymbol();
        }

        if ($transaction->getSpecificSymbol()) {
            $json["specific_symbol"] = $transaction->getSpecificSymbol();
        }

        if ($transaction->getCounterPartyAccountName()) {
            $json["counterparty_account_name"] = $transaction->getCounterPartyAccountName();
        }

        if ($transaction->getCounterPartyAccountNumber()) {
            $json["counterparty_account_number"] = $transaction->getCounterPartyAccountNumber();
        }

        if ($transaction->getLocation()) {
            $json["location"] = $transaction->getLocation();
        }

        if ($transaction->getConsigneeMessage()) {
            $json["consignee_message"] = $transaction->getConsigneeMessage();
        }

        return $json;
    }
}