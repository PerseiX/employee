<?php

namespace App\Payment\Domain\Repository;

use App\Payment\Domain\Model\Department;

interface DepartmentRepositoryInterface
{
    public function findOneById(int $id): Department;

    public function save(Department $department): void;
}