<?php

declare(strict_types=1);

namespace App\Payment\Domain\Model;

final readonly class DepartmentBonusRule
{
    public function __construct(
        public int $departmentId,
        public array $configuration,
        public string $bonusType
    ) {
    }
}
