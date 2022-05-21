<?php

namespace App\Command;

use App\Entity\BankAccount;
use App\Entity\Transaction;
use App\Service\AirBankTransactionParser;
use App\Service\CreditasTransactionParser;
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
            [
                "account" => "1683259015/3030",
                "file" => "air_bank_1683259015.csv"
            ],
            [
                "account" => "1683259023/3030",
                "file" => "air_bank_1683259023.csv"
            ],
            [
                "account" => "1021895274/6100",
                "file" => "equa_1021895274.csv"
            ],
            [
                "account" => "1024422878/6100",
                "file" => "equa_1024422878.csv"
            ],
            [
                "account" => "3211750833/6800",
                "file" => "sberbank_3211750833.csv"
            ],
            [
                "account" => "3211791088/6800",
                "file" => "sberbank_3211791088.csv"
            ],
            [
                "account" => "104834333/2250",
                "file" => "creditas_104834333.csv"
            ],
        ];

        foreach ($files as $fileCfg) {
            /** @var BankAccount $account */
            $account = $this->em->getRepository(BankAccount::class)->findOneBy(["number" => $fileCfg["account"]]);
            if (!$account) {
                $output->writeln("Account with number {$fileCfg["account"]} not found.");
                continue;
            }

            if ($account->getBank()->getId() === 1) {
                // airbank
                $parser = new AirBankTransactionParser();
            } elseif ($account->getBank()->getId() === 2) {
                $parser = new SberBankTransactionParser();
            } elseif ($account->getBank()->getId() === 3) {
                $parser = new EquaBankTransactionParser();
            } else {
                $parser = new CreditasTransactionParser();
            }

            $file = fopen(dirname(__DIR__, 2) . "/data/{$fileCfg["file"]}", "r");

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
                    $transaction->setTransactionId($transactionId)
                        ->setBankAccount($account);
                }

                $parser->updateTransactionData($transaction, $line);

                $this->em->persist($transaction);
            }
        }

        $this->em->flush();

        return Command::SUCCESS;
    }
}
