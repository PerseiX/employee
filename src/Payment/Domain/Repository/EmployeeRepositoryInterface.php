<?php

declare(strict_types=1);

namespace App\Payment\Domain\Repository;

use App\Payment\Domain\Model\Employee;

interface EmployeeRepositoryInterface
{
    public function findAll(): array;

    public function save(Employee $employee): void;
}
