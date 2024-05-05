<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Persistence\Repository;

use App\Payment\Domain\Exception\DepartmentBonusRuleNotFoundException;
use App\Payment\Domain\Model\DepartmentBonusRule;
use App\Payment\Domain\Repository\DepartmentBonusRuleRepositoryInterface;

final class InMemoryDepartmentBonusRuleRepository implements DepartmentBonusRuleRepositoryInterface
{
    private array $bonusRules;

    public function __construct()
    {
        $this->bonusRules = [
            1 => new DepartmentBonusRule(1, ['class' => 'PercentageBonusClass', 'someConfig' => 1], 'percentage'),
            2 => new DepartmentBonusRule(2, ['class' => 'FixedBonusClass', 'someConfig' => 1], 'fixed'),
        ];
    }

    public function findByDepartmentId(int $departmentId): DepartmentBonusRule
    {
        return $this->bonusRules[$departmentId] ?? throw new DepartmentBonusRuleNotFoundException();
    }

    public function save(DepartmentBonusRule $departmentBonusRule): void
    {
        $this->bonusRules[$departmentBonusRule->departmentId] = $departmentBonusRule;
    }
}
