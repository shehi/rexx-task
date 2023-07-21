<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use Carbon\Carbon;

class Event implements SerializableInterface
{
    private ?int $id = null;

    private string $name;

    private Carbon $startsAt;

    private string $apiVersion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartsAt(): Carbon
    {
        return $this->startsAt;
    }

    public function setStartsAt(Carbon $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    public function setApiVersion(string $apiVersion): self
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'starts_at' => $this->startsAt->utc(),
            'api_version' => $this->apiVersion,
        ];
    }
}
