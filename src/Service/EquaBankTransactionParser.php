<?php

namespace App\Service;

use App\Entity\Transaction;
use DateTime;

class EquaBankTransactionParser extends TransactionParser
{
    public function getTransactionId(array $data): string
    {
        return $data[11];
    }

    public function updateTransactionData(Transaction $transaction, array $data): void
    {
        $transaction
            ->setCounterPartyAccountNumber($data[2] ?: null)
            ->setCounterPartyAccountName($data[3] ?: null)
            ->setDateOfCharge($data[4] ? DateTime::createFromFormat("d.m.Y", $data[4]) : null)
            ->setDateOfIssue($data[5] ? DateTime::createFromFormat("d.m.Y", $data[5]) : null)
            ->setAmount(floatval(str_replace(",", ".", $data[6])))
            ->setCurrency($data[7])
            ->setType($data[8])
            ->setDescription($data[9] ?: null)
            ->setVariableSymbol($data[12] ?: null)
            ->setSpecificSymbol($data[13] ?: null)
            ->setConstantSymbol($data[14] ?: null)
            ->setLocation($data[15] ?: null);
    }
}