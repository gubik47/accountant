<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Orm\ManyToOne(targetEntity: BankAccount::class)]
    #[Orm\JoinColumn(name: "bank_account_id", referencedColumnName: "id")]
    private ?BankAccount $bankAccount = null;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $transactionId = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $type = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $counterPartyAccountNumber = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $counterPartyAccountName = null;

    #[ORM\Column(type: 'float')]
    private float $amount = 0;

    #[ORM\Column(type: 'string', length: 3)]
    private ?string $currency = null;

    #[ORM\Column(type: 'string', length: 25, nullable: true)]
    private ?string $variableSymbol = null;

    #[ORM\Column(type: 'string', length: 25, nullable: true)]
    private ?string $specificSymbol = null;

    #[ORM\Column(type: 'string', length: 25, nullable: true)]
    private ?string $constantSymbol = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(type: 'string', length: 500, nullable: true)]
    private ?string $note = null;

    #[ORM\Column(type: 'string', length: 500, nullable: true)]
    private ?string $consigneeMessage = null;

    #[ORM\Column(type: 'date')]
    private ?DateTimeInterface $dateOfIssue = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $dateOfCharge = null;

    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: "create")]
    private ?DateTimeInterface $created = null;

    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: "update")]
    private ?DateTimeInterface $updated = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Transaction
    {
        $this->id = $id;
        return $this;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(?string $transactionId): Transaction
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): Transaction
    {
        $this->type = $type;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Transaction
    {
        $this->description = $description;
        return $this;
    }

    public function getCounterPartyAccountNumber(): ?string
    {
        return $this->counterPartyAccountNumber;
    }

    public function setCounterPartyAccountNumber(?string $counterPartyAccountNumber): Transaction
    {
        $this->counterPartyAccountNumber = $counterPartyAccountNumber;
        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): Transaction
    {
        $this->amount = $amount;
        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): Transaction
    {
        $this->currency = $currency;
        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): Transaction
    {
        $this->source = $source;
        return $this;
    }

    public function getDateOfIssue(): ?DateTimeInterface
    {
        return $this->dateOfIssue;
    }

    public function setDateOfIssue(?DateTimeInterface $issuedOn): Transaction
    {
        $this->dateOfIssue = $issuedOn;
        return $this;
    }

    public function getCounterPartyAccountName(): ?string
    {
        return $this->counterPartyAccountName;
    }

    public function setCounterPartyAccountName(?string $counterPartyAccountName): Transaction
    {
        $this->counterPartyAccountName = $counterPartyAccountName;
        return $this;
    }

    public function getVariableSymbol(): ?string
    {
        return $this->variableSymbol;
    }

    public function setVariableSymbol(?string $variableSymbol): Transaction
    {
        $this->variableSymbol = $variableSymbol;
        return $this;
    }

    public function getSpecificSymbol(): ?string
    {
        return $this->specificSymbol;
    }

    public function setSpecificSymbol(?string $specificSymbol): Transaction
    {
        $this->specificSymbol = $specificSymbol;
        return $this;
    }

    public function getConstantSymbol(): ?string
    {
        return $this->constantSymbol;
    }

    public function setConstantSymbol(?string $constantSymbol): Transaction
    {
        $this->constantSymbol = $constantSymbol;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): Transaction
    {
        $this->location = $location;
        return $this;
    }

    public function getDateOfCharge(): ?DateTimeInterface
    {
        return $this->dateOfCharge;
    }

    public function setDateOfCharge(?DateTimeInterface $dateOfCharge): Transaction
    {
        $this->dateOfCharge = $dateOfCharge;
        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): Transaction
    {
        $this->note = $note;
        return $this;
    }

    public function getConsigneeMessage(): ?string
    {
        return $this->consigneeMessage;
    }

    public function setConsigneeMessage(?string $consigneeMessage): Transaction
    {
        $this->consigneeMessage = $consigneeMessage;
        return $this;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?DateTimeInterface $created): Transaction
    {
        $this->created = $created;
        return $this;
    }

    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?DateTimeInterface $updated): Transaction
    {
        $this->updated = $updated;
        return $this;
    }

    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    public function setBankAccount(?BankAccount $bankAccount): Transaction
    {
        $this->bankAccount = $bankAccount;
        return $this;
    }
}
