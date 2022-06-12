<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User extends BaseEntity implements JsonSerializable
{
    #[ORM\Column(type: "string")]
    protected ?string $firstName = null;

    #[ORM\Column(type: "string")]
    protected ?string $lastName = null;

    /** @var Collection<int, BankAccount> */
    #[Orm\OneToMany(mappedBy: "user", targetEntity: BankAccount::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    #[Orm\OrderBy(["number" => "ASC"])]
    private Collection $accounts;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return Collection<int, BankAccount>
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "first_name" => $this->firstName,
            "last_name" => $this->lastName,
            "total_balance" => $this->getTotalAccountBalanceSum()
        ];
    }

    public function getTotalAccountBalanceSum(): float
    {
        $total = 0;

        foreach ($this->accounts as $account) {
            $total += $account->getBalance();
        }

        return round($total, 2);
    }
}
