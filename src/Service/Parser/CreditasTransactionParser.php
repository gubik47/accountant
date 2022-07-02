<?php

namespace App\Service\Parser;

use App\Entity\Transaction;
use DateTime;
use League\Csv\Reader;
use League\Csv\Statement;

class CreditasTransactionParser extends TransactionParser
{
    /**
     * @param string $csvData
     * @return iterable<mixed>
     */
    public function parseCsvLines(string $csvData): iterable
    {
        $csvData = iconv("Windows-1250", "UTF-8", $csvData);

        $reader = Reader::createFromString($csvData);
        $reader->setHeaderOffset(3)
            ->setDelimiter(";")
            ->setEnclosure("\"");

        $stmt = (new Statement())->offset(2);

        return $stmt->process($reader);
    }

    /**
     * @param string[] $data
     * @return string
     */
    public function getTransactionId(array $data): string
    {
        // Creditas has no transaction ID in its CSV dump
        // generating an artificial one
        return substr(sha1(implode(",", [$data[3], $data[16], $data[17]])), 0, 10);
    }

    /**
     * @param Transaction $transaction
     * @param string[]       $data
     * @return void
     */
    public function updateTransactionData(Transaction $transaction, array $data): void
    {
        $transaction
            ->setDateOfIssue(DateTime::createFromFormat("d.m.Y", $data[3]))
            ->setCounterPartyAccountName($data[7] ?: null)
            ->setType($data[8])
            ->setVariableSymbol($data[9] ?: null)
            ->setSpecificSymbol($data[10] ?: null)
            ->setConstantSymbol($data[11] ?: null)
            ->setConsigneeMessage($data[13] ?: null)
            ->setNote($data[14] ?: null)
            ->setAmount($this->parseFloat($data[16]))
            ->setCurrency($data[17]);

        if (!empty($data[5]) && !empty($data[6])) {
            $transaction->setCounterPartyAccountNumber($data[5] . "/" . $data[6]);
        }
    }
}