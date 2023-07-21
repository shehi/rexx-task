<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\Entities\SerializableInterface;

abstract readonly class HydratingRepositoryInterface
{
    abstract protected function hydrateEntityObj(array $datum): SerializableInterface;
}
