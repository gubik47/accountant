<?php

namespace App\Service\Transaction;

use App\Entity\Transaction;

abstract class TransactionParser
{
    abstract public function parseCsvLines(string $csvData): iterable;

    abstract public function getTransactionId(array $data): string;

    abstract public function updateTransactionData(Transaction $transaction, array $data): void;

    protected function parseFloat(string $data): float
    {
        return floatval(str_replace([",", " "], [".", ""], $data));
    }
}