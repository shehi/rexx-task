<?php

namespace App\Domain\Entities;

interface SerializableInterface
{
    public function serialize(): array;
}
