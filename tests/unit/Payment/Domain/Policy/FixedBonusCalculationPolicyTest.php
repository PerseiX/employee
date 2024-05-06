<?php

namespace App\Tests\unit\Payment\Domain\Policy;

use App\Payment\Domain\Exception\BonusCalculationPolicyCanNotBeCreatedException;
use App\Payment\Domain\Policy\FixedBonusCalculationPolicy;
use PHPUnit\Framework\TestCase;

final class FixedBonusCalculationPolicyTest extends TestCase
{

    public function testCalculateBonus(): void
    {
        $policy = FixedBonusCalculationPolicy::createFromConfig(['bonusPerYear' => 80, 'maxNumberOfYear' => 10]);

        self::assertEquals(
            400,
            $policy->calculateBonus(1500, 5)
        );
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(BonusCalculationPolicyCanNotBeCreatedException::class);

        FixedBonusCalculationPolicy::createFromConfig(['invalid' => 80]);
    }
}
