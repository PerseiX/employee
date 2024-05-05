<?php

declare(strict_types=1);

namespace App\Payment\Domain\Policy;

final class NoBonusCalculationPolicy implements BonusCalculationPolicy
{

    public function calculateBonus(float $baseSalary, int $yearsOfExperience): float
    {
        return $baseSalary;
    }

    public static function createFromConfig(array $config): BonusCalculationPolicy
    {
        return new self();
    }
}
