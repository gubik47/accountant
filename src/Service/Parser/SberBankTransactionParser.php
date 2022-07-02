<?php

namespace App\Service\Parser;

use App\Entity\Transaction;
use DateTime;
use League\Csv\Reader;

class SberBankTransactionParser extends TransactionParser
{
    /**
     * @param string[] $data
     * @return string
     */
    public function getTransactionId(array $data): string
    {
        // Sberbank has no transaction ID in its CSV dump
        // generating an artificial one
        return substr(sha1(implode(",", $data)), 0, 10);
    }

    /**
     * @param Transaction $transaction
     * @param string[]    $data
     * @return void
     */
    public function updateTransactionData(Transaction $transaction, array $data): void
    {
        $transaction
            ->setDateOfIssue(DateTime::createFromFormat("d.m.Y", $data[1]))
            ->setType($data[2])
            ->setDescription($data[3] ?: null)
            ->setCounterPartyAccountNumber($data[4] ?: null)
            ->setAmount($this->parseFloat($data[5]))
            ->setCurrency($data[6]);
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