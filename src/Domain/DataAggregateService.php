<?php

declare(strict_types=1);

namespace App\Domain;

use App\Application\Repository\EmployeeRepository;
use App\Application\Repository\EventEmployeeRepository;
use App\Application\Repository\EventRepository;
use Carbon\Carbon;

readonly class DataAggregateService
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
        private EventRepository $eventRepository,
        private EventEmployeeRepository $eventEmployeeRepository,
    ) {
    }

    public function getAllEmployees(): array
    {
        return $this->employeeRepository->fetchAll();
    }

    public function getAllEvents(): array
    {
        return $this->eventRepository->fetchAll();
    }

    public function aggregate(): array
    {
        $eventId = isset($_POST['event_id']) && $_POST['event_id'] ? (int)$_POST['event_id'] : null;
        $employeeId = isset($_POST['employee_id']) && $_POST['employee_id'] ? (int)$_POST['employee_id'] : null;
        $date = isset($_POST['event_date']) && $_POST['event_date'] ? new Carbon($_POST['event_date']) : null;

        return $this->eventEmployeeRepository->fetchAll($eventId, $employeeId, $date);
    }
}
