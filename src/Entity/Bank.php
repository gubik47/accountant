<?php

namespace App\Entity;

use App\Repository\BankRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: BankRepository::class)]
class Bank extends BaseEntity implements JsonSerializable
{
    #[ORM\Column(type: "string", length: 100)]
    protected ?string $name = null;

    #[ORM\Column(type: "string", length: 5)]
    protected ?string $code = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Bank
    {
        $this->name = $name;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): Bank
    {
        $this->code = $code;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "code" => $this->code
        ];
    }
}
