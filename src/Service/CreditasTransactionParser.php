<?php

namespace App\Service;

use App\Entity\Transaction;
use DateTime;

class CreditasTransactionParser extends TransactionParser
{
    public function getTransactionId(array $data): string
    {
        // Creditas has no transaction ID in its CSV dump
        // generating an artificial one
        return substr(sha1(implode(",", $data)), 0, 10);
    }

    public function updateTransactionData(Transaction $transaction, array $data): void
    {
        $data = array_map(function ($value) {
            return iconv("Windows-1250", "UTF-8", $value);
        }, $data);

        $transaction
            ->setDateOfIssue(DateTime::createFromFormat("d.m.Y", $data[3]))
            ->setCounterPartyAccountName($data[7] ?: null)
            ->setType($data[8])
            ->setVariableSymbol($data[9] ?: null)
            ->setSpecificSymbol($data[10] ?: null)
            ->setConstantSymbol($data[11] ?: null)
            ->setConsigneeMessage($data[13] ?: null)
            ->setNote($data[14] ?: null)
            ->setAmount(floatval(str_replace(",", ".", $data[16])))
            ->setCurrency($data[17]);

        if (!$data[5]) {
            $transaction->setCounterPartyAccountNumber($transaction->getBankAccount()->getNumber());
        } else {
            $transaction->setCounterPartyAccountNumber($data[5] . "/" . $data[6]);
        }
    }
}