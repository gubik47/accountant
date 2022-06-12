<?php

namespace App\Component\Model;

use JsonSerializable;

abstract class Base implements JsonSerializable
{
    protected array $data = [];

    public function jsonSerialize(): array
    {
        $serialized = [];
        foreach ($this->data as $key => $value) {
            $serialized[$key] = $value;
        }
        return $serialized;
    }

    public function set(string $name, mixed $value): self
    {
        $this->data[$name] = $value;
        return $this;
    }

    public function get(string $name): mixed
    {
        return $this->data[$name] ?? null;
    }
}