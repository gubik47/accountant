<?php

namespace App\Service\Parser;

use App\Entity\Transaction;
use DateTime;
use League\Csv\Reader;

class AirBankTransactionParser extends TransactionParser
{
    /**
     * @param string[] $data
     * @return string
     */
    public function getTransactionId(array $data): string
    {
        return $data[32];
    }

    /**
     * @param Transaction $transaction
     * @param string[]    $data
     * @return void
     */
    public function updateTransactionData(Transaction $transaction, array $data): void
    {
        $transaction
            ->setDateOfIssue($data[0] ? DateTime::createFromFormat("d/m/Y", $data[0]) : null)
            ->setType($data[2])
            ->setCurrency($data[4])
            ->setAmount($this->parseFloat($data[5]))
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

    /**
     * @param string $csvData
     * @return iterable<mixed>
     */
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