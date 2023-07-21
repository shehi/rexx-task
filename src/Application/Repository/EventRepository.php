<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\Entities\Event;
use App\Infrastructure\Db\RepositoryInterface;
use Carbon\Carbon;

readonly class EventRepository extends HydratingRepositoryInterface
{
    public const TABLE = 'events';

    public function __construct(private RepositoryInterface $repository)
    {
    }

    public function fetchOneById(int $id): ?Event
    {
        $row = $this->repository->fetchOneBy(self::TABLE, 'id', $id);
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

    public function store(Event $data): int
    {
        return $this->repository->store(self::TABLE, $data);
    }

    protected function hydrateEntityObj(array $datum): Event
    {
        return (new Event())
            ->setId($datum['id'])
            ->setName($datum['name'])
            ->setStartsAt(new Carbon($datum['starts_at']))
            ->setApiVersion($datum['api_version']);
    }
}
