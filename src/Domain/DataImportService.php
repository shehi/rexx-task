<?php

declare(strict_types=1);

namespace App\Domain;

use App\Application\Repository\EmployeeRepository;
use App\Application\Repository\EventEmployeeRepository;
use App\Application\Repository\EventRepository;
use App\Domain\Entities\Employee;
use App\Domain\Entities\Event;
use App\Domain\Entities\EventEmployee;
use Carbon\Carbon;
use JsonException;

readonly class DataImportService
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
        private EventRepository $eventRepository,
        private EventEmployeeRepository $eventEmployeeRepository,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function import(): void
    {
        $jsonData = file_get_contents(
            sprintf('%s/data.json', dirname(__DIR__, 2))
        );
        $rawData = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);

        foreach ($rawData as $datum) {
            $employee = $this->employeeRepository->fetchOneByEmail($datum['employee_mail']);
            if (!$employee) {
                $employeeObj = (new Employee())
                    ->setName($datum['employee_name'])
                    ->setEmail($datum['employee_mail']);
                $this->employeeRepository->store($employeeObj);
                $employee = $this->employeeRepository->fetchOneByEmail($datum['employee_mail']);
            }

            $event = $this->eventRepository->fetchOneById($datum['event_id']);
            if (!$event) {
                $eventDate = new Carbon(
                    $datum['event_date'],
                    version_compare('1.0.17+60', $datum['version'], '>')
                        ? 'Europe/Berlin'
                        : 'UTC'
                );

                $eventObj = (new Event())
                    ->setId((int)$datum['event_id'])
                    ->setName($datum['event_name'])
                    ->setStartsAt($eventDate)
                    ->setApiVersion($datum['version']);
                $this->eventRepository->store($eventObj);
                $event = $this->eventRepository->fetchOneById($datum['event_id']);
            }

            $participationObj = (new EventEmployee())
                ->setId((int)$datum['participation_id'])
                ->setEvent($event)
                ->setEmployee($employee)
                ->setFee((float)$datum['participation_fee']);
            $this->eventEmployeeRepository->store($participationObj);
        }
    }
}
