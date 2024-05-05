<?php

declare(strict_types=1);

namespace App\Payment\Domain\Policy;

use DateTimeImmutable;
use DateTimeInterface;

final class FixedBonusCalculationPolicy implements BonusCalculationPolicy
{
    private function __construct(
        private readonly float $bonusPerYear,
        private readonly int $maxNumberOfYear
    ) {
    }

    public function calculateBonus(float $baseSalary, int $yearsOfExperience): float
    {
        if ($this->maxNumberOfYear >= $yearsOfExperience) {
            return $this->bonusPerYear * $yearsOfExperience;
        }

        return $this->maxNumberOfYear * $yearsOfExperience;
    }

    public static function createFromConfig(array $config): self
    {
        if (!array_key_exists('bonusPerYear', $config)) {
            throw new BonusCalculationPolicyCanNotBeCreatedException();
        }

        if (!array_key_exists('maxNumberOfYear', $config)) {
            throw new BonusCalculationPolicyCanNotBeCreatedException();
        }

        return new self($config['bonusPerYear'], $config['maxNumberOfYear']);
    }
}
