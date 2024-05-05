<?php

declare(strict_types=1);

namespace App\Tests\unit\Payment\Domain\Factory;

use App\Payment\Domain\Policy\BonusCalculationPolicy;
use App\Payment\Domain\Policy\BonusCalculationPolicyCanNotBeCreatedException;

final readonly class BonusCalculationPolicyFake implements BonusCalculationPolicy
{
    public function __construct(private int $fixedBonus = 100)
    {
    }

    public function calculateBonus(float $baseSalary, int $yearsOfExperience): float
    {
        return $this->fixedBonus;
    }

    public static function createFromConfig(array $config): self
    {
        if (!array_key_exists('value', $config)) {
            throw new BonusCalculationPolicyCanNotBeCreatedException();
        }

        return new self($config['value']);
    }
}
