<?php

namespace App\Command;

use App\Entity\Transaction;
use App\Service\AirBankTransactionParser;
use App\Service\EquaBankTransactionParser;
use App\Service\SberBankTransactionParser;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'acc:import:transactions',
    description: 'Importuje bankovni transakce z CSV',
)]
class ImportTransactionsCommand extends Command
{
    private LoggerInterface $logger;
    private EntityManagerInterface $em;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->em = $em;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $files = [
            "air_bank.csv",
            "equa.csv",
            "sb.csv"
        ];

        foreach ($files as $fileName) {
            if ($fileName === "air_bank.csv") {
                $parser = new AirBankTransactionParser();
            } elseif ($fileName === "equa.csv") {
                $parser = new EquaBankTransactionParser();
            } else {
                $parser = new SberBankTransactionParser();
            }

            $file = fopen(dirname(__DIR__, 2) . "/data/$fileName", "r");

            $ctr = 0;
            while (($line = fgetcsv($file, 0, ";")) !== false) {
                $ctr++;
                if ($ctr === 1) {
                    continue;
                }

                $repo = $this->em->getRepository(Transaction::class);

                $transactionId = $parser->getTransactionId($line);

                $transaction = $repo->findOneBy(["transactionId" => $transactionId]);
                if (!$transaction) {
                    $transaction = new Transaction();
                    $transaction->setTransactionId($transactionId);
                }

                $parser->updateTransactionData($transaction, $line);

                $this->em->persist($transaction);
            }
        }

        $this->em->flush();

        return Command::SUCCESS;
    }
}
