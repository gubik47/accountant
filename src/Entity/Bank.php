<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Bank extends BaseEntity
{
    #[ORM\Column(type: "string", length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: "string", length: 5)]
    private ?string $code = null;

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
}
