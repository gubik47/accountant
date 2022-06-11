<?php

namespace App\Service\Transaction;

use App\Entity\Transaction;
use DateTime;
use League\Csv\Reader;

class AirBankTransactionParser extends TransactionParser
{
    public function getTransactionId(array $data): string
    {
        return $data[32];
    }

    public function updateTransactionData(Transaction $transaction, array $data): void
    {
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
            ->setDateOfCharge($data[29] ? DateTime::createFromFormat("d/m/Y", $data[29]) : null);
    }

    public function parseCsvLines(string $csvData): iterable
    {
        $csvData = iconv("Windows-1250", "UTF-8", $csvData);

        $reader = Reader::createFromString($csvData);
        $reader->setHeaderOffset(0)
            ->setDelimiter(";")
            ->setEnclosure("\"");

        return $reader->getRecords();
    }
}