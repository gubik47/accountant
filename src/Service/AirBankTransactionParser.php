<?php

namespace App\Service;

use App\Entity\Transaction;
use DateTime;

class AirBankTransactionParser extends TransactionParser
{
    public function getTransactionId(array $data): string
    {
        return iconv("Windows-1250", "UTF-8", $data[32]);
    }

    public function updateTransactionData(Transaction $transaction, array $data): void
    {
        $data = array_map(function ($value) {
            return iconv("Windows-1250", "UTF-8", $value);
        }, $data);

        $transaction
            ->setDateOfIssue($data[0] ? DateTime::createFromFormat("d/m/Y", $data[0]) : null)
            ->setType($data[2])
            ->setCurrency($data[4])
            ->setAmount(floatval(str_replace(",", ".", $data[5])))
            ->setCounterPartyAccountName($data[9] ?: null)
            ->setCounterPartyAccountNumber($data[10] ?: null)
            ->setVariableSymbol($data[12] ?: null)
            ->setConstantSymbol($data[13] ?: null)
            ->setSpecificSymbol($data[14] ?: null)
            ->setNote($data[17] ?: null)
            ->setConsigneeMessage($data[19] ?: null)
            ->setLocation($data[24] ?: null)
            ->setDateOfCharge($data[29] ? DateTime::createFromFormat("d/m/Y", $data[29]) : null)
            ->setSource(Transaction::SOURCE_AIRBANK);
    }
}