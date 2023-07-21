<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class EventEmployee implements SerializableInterface
{
    private ?int $id = null;

    private Event $event;

    private Employee $employee;

    private float $fee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getFee(): float
    {
        return $this->fee;
    }

    public function setFee(float $fee): self
    {
        $this->fee = $fee;

        return $this;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event->getId(),
            'employee_id' => $this->employee->getId(),
            'fee' => $this->fee,
        ];
    }
}
