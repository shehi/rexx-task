<?php

declare(strict_types=1);

use App\Application\Repository\EmployeeRepository;
use App\Application\Repository\EventEmployeeRepository;
use App\Application\Repository\EventRepository;
use App\Domain\DataAggregateService;
use App\Infrastructure\Db\Repository;

require_once "../vendor/autoload.php";

// No IoC (Inversion of Control) container was implemented, hence the lack of DI and this ugliness.
$dbRepo = new Repository();
$service = (new DataAggregateService(
    new EmployeeRepository($dbRepo),
    new EventRepository($dbRepo),
    new EventEmployeeRepository($dbRepo),
));

$options['employees'] = $service->getAllEmployees();
$options['events'] = $service->getAllEvents();
$data = $service->aggregate();

?>

<html lang="en">
    <head>
        <title>Events</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <main class="container-xl">
            <form class="mt-5 d-flex flex-row justify-content-around" method="POST">
                <fieldset >
                    <label class="form-control">
                        Event
                        <select name="event_id">
                            <option value="">ALL</option>
                            <?php foreach ($options['events'] as $event): ?>
                                <option value="<?php echo $event->getId() ?>" <?php if ($event->getId() == ($_POST['event_id'] ?? null)): ?>selected<?php endif; ?>>
                                    <?php echo $event->getName() ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </fieldset>
                <fieldset >
                    <label class="form-control">
                        Employee
                        <select name="employee_id">
                            <option value="">ALL</option>
                            <?php foreach ($options['employees'] as $employee): ?>
                            <option value="<?php echo $employee->getId() ?>" <?php if ($employee->getId() == ($_POST['employee_id'] ?? null)): ?>selected<?php endif; ?>>
                                <?php echo $employee->getName() ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </fieldset>
                <fieldset>
                    <label class="form-control">
                        Event Date
                        <input type="date" name="event_date" value="<?php echo $_POST['event_date'] ?>" />
                    </label>
                </fieldset>
                <fieldset class="ms-5">
                    <button type="submit">Filter</button>
                </fieldset>
            </form>
            <table class="table table-bordered table-striped mt-5">
                <thead class="table-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Event</th>
                        <th class="text-center">Date</th>
                        <th class="text-end">Fee</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $datum): ?>
                    <tr>
                        <td><?php echo $datum['employee_name'] ?></td>
                        <td><?php echo $datum['event_name'] ?></td>
                        <td class="text-center"><?php echo $datum['event_date'] ?> UTC</td>
                        <td class="text-end"><?php echo $datum['participation_fee'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </body>
</html>
