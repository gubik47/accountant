<?php

namespace App\Service;

use App\Entity\Transaction;

abstract class TransactionParser
{
    abstract public function getTransactionId(array $data): string;

    abstract public function updateTransactionData(Transaction $transaction, array $data): void;
}