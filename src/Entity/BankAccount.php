<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
class BankAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Orm\ManyToOne(targetEntity: Bank::class)]
    #[Orm\JoinColumn(name: "bank_id", referencedColumnName: "id")]
    private ?Bank $bank = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $owner = null;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $number = null;

    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: "create")]
    private ?DateTimeInterface $created = null;

    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: "update")]
    private ?DateTimeInterface $updated = null;

    #[Orm\OneToMany(mappedBy: "bankAccount", targetEntity: Transaction::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    #[Orm\OrderBy(["dateOfIssue" => "DESC"])]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): BankAccount
    {
        $this->id = $id;
        return $this;
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

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?DateTimeInterface $created): BankAccount
    {
        $this->created = $created;
        return $this;
    }

    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?DateTimeInterface $updated): BankAccount
    {
        $this->updated = $updated;
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
