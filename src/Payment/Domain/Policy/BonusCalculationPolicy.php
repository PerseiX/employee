<?php

declare(strict_types=1);

namespace App\Payment\Domain\Policy;

interface BonusCalculationPolicy
{
    public function calculateBonus(float $baseSalary, int $yearsOfExperience): float;

    public static function createFromConfig(array $config): self;
}
