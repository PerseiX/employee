<?php

declare(strict_types=1);

namespace App\Tests\unit\Payment\Domain\Policy;

use App\Payment\Domain\Policy\BonusCalculationPolicyCanNotBeCreatedException;
use App\Payment\Domain\Policy\PercentageBonusCalculationPolicy;
use PHPUnit\Framework\TestCase;

final class PercentageBonusCalculationPolicyTest extends TestCase
{
    public function testCreatingFromConfig(): void
    {
        $policy = PercentageBonusCalculationPolicy::createFromConfig(['percent' => 16]);

        self::assertEquals(new PercentageBonusCalculationPolicy(16), $policy);
    }

    public function testConfigurationIsNotValid(): void
    {
        $this->expectException(BonusCalculationPolicyCanNotBeCreatedException::class);

        PercentageBonusCalculationPolicy::createFromConfig(['invalid_config' => 16]);
    }

    public function testPolicyCalculation(): void
    {
        $policy = PercentageBonusCalculationPolicy::createFromConfig(['percent' => 10]);

        self::assertEquals(
            145.05,
            $policy->calculateBonus(1450.5, 3)
        );
    }
}
