<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\Entities\Employee;
use App\Infrastructure\Db\RepositoryInterface;

readonly class EmployeeRepository extends HydratingRepositoryInterface
{
    public const TABLE = 'employees';

    public function __construct(private RepositoryInterface $repository)
    {
    }

    public function fetchOneByEmail(string $email): ?Employee
    {
        $row = $this->repository->fetchOneBy(self::TABLE, 'email', $email);
        if (!$row) {
            return null;
        }

        return $this->hydrateEntityObj($row);
    }

    public function fetchAll(): array
    {
        $iterator = $this->repository->fetchAll(self::TABLE);
        $data = [];
        while ($row = $iterator?->current()) {
            $data[] = $this->hydrateEntityObj($row);
            $iterator?->next();
        }

        return $data;
    }

    public function store(Employee $data): int
    {
        return $this->repository->store(self::TABLE, $data);
    }

    protected function hydrateEntityObj(array $datum): Employee
    {
        return (new Employee())
            ->setId($datum['id'])
            ->setEmail($datum['email'])
            ->setName($datum['name']);
    }
}
