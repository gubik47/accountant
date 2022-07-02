<?php

namespace App\Service\Parser;

use App\Entity\Transaction;
use DateTime;
use League\Csv\Reader;

class EquaBankTransactionParser extends TransactionParser
{
    /**
     * @param string[] $data
     * @return string
     */
    public function getTransactionId(array $data): string
    {
        return $data[11];
    }

    /**
     * @param Transaction $transaction
     * @param string[]    $data
     * @return void
     */
    public function updateTransactionData(Transaction $transaction, array $data): void
    {
        $transaction
            ->setCounterPartyAccountNumber($data[2] ?: null)
            ->setCounterPartyAccountName($data[3] ?: null)
            ->setDateOfCharge($data[4] ? DateTime::createFromFormat("d.m.Y", $data[4]) : null)
            ->setDateOfIssue($data[5] ? DateTime::createFromFormat("d.m.Y", $data[5]) : null)
            ->setAmount($this->parseFloat($data[6]))
            ->setCurrency($data[7])
            ->setType($data[8])
            ->setDescription($data[9] ?: null)
            ->setVariableSymbol($data[12] ?: null)
            ->setSpecificSymbol($data[13] ?: null)
            ->setConstantSymbol($data[14] ?: null)
            ->setLocation($data[15] ?: null);
    }

    /**
     * @param string $csvData
     * @return iterable<mixed>
     */
    public function parseCsvLines(string $csvData): iterable
    {
        $reader = Reader::createFromString($csvData);
        $reader->setHeaderOffset(0)
            ->setDelimiter(";")
            ->setEnclosure("\"");

        return $reader->getRecords();
    }
}