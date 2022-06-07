<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class BankAccount extends BaseEntity
{
    #[Orm\ManyToOne(targetEntity: Bank::class)]
    #[Orm\JoinColumn(name: "bank_id", referencedColumnName: "id")]
    private ?Bank $bank = null;

    #[Orm\ManyToOne(targetEntity: User::class, inversedBy: "accounts")]
    #[Orm\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private ?User $user = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $owner = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $number = null;

    #[Orm\OneToMany(mappedBy: "bankAccount", targetEntity: Transaction::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    #[Orm\OrderBy(["dateOfIssue" => "DESC"])]
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

    public function getUser(): ?Bank
    {
        return $this->user;
    }

    public function setUser(?Bank $user): BankAccount
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
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection|array
    {
        return $this->transactions;
    }
}
