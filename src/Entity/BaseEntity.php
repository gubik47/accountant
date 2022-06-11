<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Gedmo\Mapping\Annotation as Gedmo;
use ReflectionClass;

abstract class BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    protected ?int $id = null;

    #[ORM\Column(type: "datetime")]
    #[Gedmo\Timestampable(on: "create")]
    protected ?DateTimeInterface $created = null;

    #[ORM\Column(type: "datetime")]
    #[Gedmo\Timestampable(on: "update")]
    protected ?DateTimeInterface $updated = null;

    /**
     * @var ClassMetadata[]
     */
    protected static array $metadataCache = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?DateTimeInterface $created): static
    {
        $this->created = $created;
        return $this;
    }

    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?DateTimeInterface $updated): static
    {
        $this->updated = $updated;
        return $this;
    }

    public function updateProperties(EntityManagerInterface $em, array $data): static
    {
        $this->getClassMetadata($em);

        $ref = new ReflectionClass($this);

        foreach ($data as $property => $value) {
            if (!$ref->hasProperty($property)) {
                continue;
            }

            try {
                $mapping = static::$metadataCache[get_class($this)]->getFieldMapping($property);
            } catch (MappingException $ex) {
                continue;
            }

            if (!empty($mapping["id"])) {
                // primary keys cannot be updated
                continue;
            }

            if (str_contains($mapping["type"], Types::DATE_MUTABLE)) {
                $value = $value ? DateTime::createFromFormat("Y-m-d H:i:s", $value) : null;
            } elseif ($mapping["type"] === Types::JSON) {
                $value = $value ? json_decode($value, true) : null;
            } elseif ($mapping["type"] === Types::INTEGER) {
                $value = intval($value);
            } elseif ($mapping["type"] === Types::FLOAT) {
                $value = floatval($value);
            } elseif ($mapping["type"] === Types::BOOLEAN) {
                $value = boolval($value);
            }

            $this->{$property} = $value;
        }

        return $this;
    }

    private function getClassMetadata(EntityManagerInterface $em): void
    {
        if (empty(static::$metadataCache[get_class($this)])) {
            static::$metadataCache[get_class($this)] = $em->getClassMetadata(get_class($this));
        }
    }
}