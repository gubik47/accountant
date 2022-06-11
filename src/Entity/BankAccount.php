<?php

namespace App\Entity;

use App\Repository\BankAccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: BankAccountRepository::class)]
class BankAccount extends BaseEntity implements JsonSerializable
{
    #[Orm\ManyToOne(targetEntity: Bank::class)]
    #[Orm\JoinColumn(name: "bank_id", referencedColumnName: "id")]
    private ?Bank $bank = null;

    #[Orm\ManyToOne(targetEntity: User::class, inversedBy: "accounts")]
    #[Orm\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private ?User $user = null;

    #[ORM\Column(type: "string", length: 100)]
    protected ?string $name = null;

    #[ORM\Column(type: "string", length: 100)]
    protected ?string $owner = null;

    #[ORM\Column(type: "string", length: 50)]
    protected ?string $number = null;

    #[Orm\OneToMany(mappedBy: "bankAccount", targetEntity: Transaction::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    #[Orm\OrderBy(["dateOfIssue" => "DESC"])]
    /** @var Collection<Transaction> */
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getBank(): ?Bank
    {
        return $this->bank;
    }

    public function setBank(?Bank $bank): BankAccount
    {
        $this->bank = $bank;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): BankAccount
    {
        $this->user = $user;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): BankAccount
    {
        $this->name = $name;
        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(?string $owner): BankAccount
    {
        $this->owner = $owner;
        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): BankAccount
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return Collection<Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function getBalance(): float
    {
        $total = 0;

        foreach ($this->transactions as $transaction) {
            $total += $transaction->getAmount();
        }

        return round($total, 2);
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "bank" => [
                "id" => $this->bank->getId(),
                "name" => $this->bank->getName(),
                "code" => $this->bank->getCode()
            ],
            "name" => $this->name,
            "number" => $this->number,
            "owner" => $this->owner,
            "balance" => $this->getBalance()
        ];
    }
}
