<?php

declare(strict_types=1);

namespace App\Payment\Domain\Repository;

use App\Payment\Domain\Model\DepartmentBonusRule;

interface DepartmentBonusRuleRepositoryInterface
{
    public function findByDepartmentId(int $departmentId): DepartmentBonusRule;

    public function save(DepartmentBonusRule $departmentBonusRule): void;
}
