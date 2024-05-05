<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Persistence\Repository;

use App\Payment\Domain\Exception\DepartmentNotFoundException;
use App\Payment\Domain\Model\Department;
use App\Payment\Domain\Repository\DepartmentRepositoryInterface;

final class InMemoryDepartmentRepository implements DepartmentRepositoryInterface
{
    private array $departments;

    public function __construct()
    {
        $this->departments = [
            1 => new Department(1, 'HR'),
            2 => new Department(2, 'IT'),
            3 => new Department(3, 'Management'),
        ];
    }

    public function findOneById(int $id): Department
    {
        return $this->departments[$id] ?? throw new DepartmentNotFoundException();
    }

    public function save(Department $department): void
    {
        $this->departments[$department->getId()] = $department;
    }
}
