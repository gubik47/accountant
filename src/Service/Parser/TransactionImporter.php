<?php

namespace App\Service\Parser;

use App\Entity\Bank;
use App\Entity\BankAccount;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

class TransactionImporter
{
    private EntityManagerInterface $em;
    private TransactionRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Transaction::class);
    }

    public function importTransactions(BankAccount $account, string $csvData): int
    {
        if (!$account->getBank()) {
            return 0;
        }

        $parser = $this->getParser($account->getBank());

        $imported = 0;
        foreach ($parser->parseCsvLines($csvData) as $line) {
            $transactionId = $parser->getTransactionId(array_values($line));

            $transaction = $this->repo->findOneBy(["transactionId" => $transactionId, "bankAccount" => $account]);
            if (!$transaction) {
                $transaction = new Transaction();
                $transaction->setTransactionId($transactionId)
                    ->setBankAccount($account);

                $imported++;
            }

            $parser->updateTransactionData($transaction, array_values($line));

            $this->em->persist($transaction);
        }

        $this->em->flush();

        return $imported;
    }

    private function getParser(Bank $bank): TransactionParser
    {
        if ($bank->getId() === 1) {
            // airbank
            return new AirBankTransactionParser();
        } elseif ($bank->getId() === 2) {
            // sberbank
            return new SberBankTransactionParser();
        } elseif ($bank->getId() === 3) {
            // equabank
            return new EquaBankTransactionParser();
        } elseif ($bank->getId() === 4) {
            // creditas
            return new CreditasTransactionParser();
        } else {
            throw new RuntimeException("Cannot parse CSV file for bank {$bank->getId()}.");
        }
    }
}