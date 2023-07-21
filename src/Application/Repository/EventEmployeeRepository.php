<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\Entities\EventEmployee;
use App\Infrastructure\Db\RepositoryInterface;
use Carbon\Carbon;

readonly class EventEmployeeRepository
{
    public const TABLE = 'events_employees';

    public function __construct(private RepositoryInterface $repository)
    {
    }

    public function fetchAll(?int $eventId = null, ?int $employeeId = null, ?Carbon $date = null): array
    {
        $wheres = [];
        $eventId && $wheres[] = 'ee.event_id = ?';
        $employeeId && $wheres[] = 'ee.employee_id = ?';

        $params = [];
        $date && $params[] = sprintf('%s%%', $date->toDateString());
        $eventId && $params[] = $eventId;
        $employeeId && $params[] = $employeeId;

        return $this->repository->fetchRaw(
            sprintf(
                '
                    SELECT
                        ee.id AS participation_id,
                        ee.fee AS participation_fee,
                        ev.id AS event_id,
                        ev.name AS event_name,
                        ev.starts_at AS event_date,
                        ev.api_version AS version,
                        ep.name AS employee_name,
                        ep.email AS employee_mail
                    FROM events_employees AS ee 
                    INNER JOIN events AS ev ON ev.id = ee.event_id AND %s
                    INNER JOIN employees AS ep ON ep.id = ee.employee_id
                    WHERE %s
                ',
                $date ? 'ev.starts_at LIKE ?' : '1',
                $wheres ? implode(' AND ', $wheres) : '1',
            ),
            $params,
        );
    }

    public function store(EventEmployee $data): int
    {
        return $this->repository->store(self::TABLE, $data);
    }
}
