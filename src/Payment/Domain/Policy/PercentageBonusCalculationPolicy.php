<?php

declare(strict_types=1);

namespace App\Payment\Domain\Policy;

use App\Payment\Domain\Exception\BonusCalculationPolicyCanNotBeCreatedException;

final class PercentageBonusCalculationPolicy implements BonusCalculationPolicy
{
    public function __construct(private readonly int $percent)
    {
    }

    public function calculateBonus(float $baseSalary, int $yearsOfExperience): float
    {
        return $baseSalary * ($this->percent / 100);
    }

    public static function createFromConfig(array $config): self
    {
        if (!array_key_exists('percent', $config)) {
            throw new BonusCalculationPolicyCanNotBeCreatedException();
        }

        return new self($config['percent']);
    }
}
