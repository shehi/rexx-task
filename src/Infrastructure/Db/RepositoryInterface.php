<?php

declare(strict_types=1);

namespace App\Infrastructure\Db;

use App\Domain\Entities\SerializableInterface;
use Iterator;

interface RepositoryInterface
{
    public function fetchOneBy(string $table, string $by, mixed $value): ?array;

    public function fetchAll(string $table): ?Iterator;

    public function fetchRaw(string $query, array $params): ?array;

    public function store(string $table, SerializableInterface $serializable): int;
}
