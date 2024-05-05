<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Persistence\Repository;

use App\Payment\Domain\Model\Employee;
use App\Payment\Domain\Repository\EmployeeRepositoryInterface;
use DateTime;

final class InMemoryEmployeeRepository implements EmployeeRepositoryInterface
{
    private array $employees;

    public function __construct()
    {
        $this->employees = [
            1 => new Employee(1, 'Jan', 'Kowalski', 1, 1650, new DateTime('2020-10-01')),
            2 => new Employee(2, 'John', 'Testowy', 2, 2250, new DateTime('2018-02-01')),
        ];
    }

    public function save(Employee $employee): void
    {
        $this->employees[$employee->getId()] = $employee;
    }

    public function findAll(): array
    {
        return $this->employees;
    }
}
