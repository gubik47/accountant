<?php

namespace App\Tests\Unit\Service\Parser;

use App\Entity\BankAccount;
use App\Entity\Transaction;
use App\Service\Parser\CreditasTransactionParser;
use PHPUnit\Framework\TestCase;

class CreditasTransactionParserTest extends TestCase
{
    private array $validData;
    private CreditasTransactionParser $parser;

    public function setUp(): void
    {
        $this->parser = new CreditasTransactionParser();

        $this->validData = [
            "104834333",
            "2250",
            "Spořicí účet +",
            "30.06.2022",
            "",
            "",
            "2250",
            "Banka CREDITAS a.s.",
            "Srážková daň",
            "",
            "",
            "",
            "",
            "Srážková daň",
            "Úrok z kladného zůstatku účtu 104834333",
            "Debit",
            "-176,28",
            "CZK",
            "Služby",
        ];

//        $this->validData = array_map(function ($cell) {
//            return iconv("UTF-8", "Windows-1250", $cell);
//        }, $this->validData);
    }

    /**
     * @test
     */
    public function Should_ReturnValidTransactionId(): void
    {
        $expected = "37b8bbdbb5";

        $actual = $this->parser->getTransactionId($this->validData);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function Should_UpdateTransactionCorrectly(): void
    {
        $transaction = new Transaction();

        $this->parser->updateTransactionData($transaction, $this->validData);

        $this->assertEquals("-176.28", $transaction->getAmount());
        $this->assertNull($transaction->getCounterPartyAccountNumber());
    }

    /**
     * @test
     */
    public function Should_UpdateTransactionCorrectlyWithExplicitCounterPartyAccountNumber(): void
    {
        $transaction = new Transaction();

        $data = $this->validData;
        $data[5] = "123456";
        $data[6] = "1000";

        $this->parser->updateTransactionData($transaction, $data);

        $this->assertEquals("-176.28", $transaction->getAmount());
        $this->assertEquals($data[5] . "/" . $data[6], $transaction->getCounterPartyAccountNumber());
    }
}
