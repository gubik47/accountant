<?php

namespace App\Service\Transaction;

use App\Entity\Transaction;

abstract class TransactionParser
{
    /**
     * @param string $csvData
     * @return iterable<mixed>
     */
    abstract public function parseCsvLines(string $csvData): iterable;

    /**
     * @param string[] $data
     * @return string
     */
    abstract public function getTransactionId(array $data): string;

    /**
     * @param Transaction $transaction
     * @param string[]    $data
     * @return void
     */
    abstract public function updateTransactionData(Transaction $transaction, array $data): void;

    protected function parseFloat(string $data): float
    {
        return floatval(str_replace([",", " "], [".", ""], $data));
    }
}