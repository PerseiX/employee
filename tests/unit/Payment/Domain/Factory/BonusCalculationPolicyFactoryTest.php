<?php

namespace App\Tests\unit\Payment\Domain\Factory;

use App\Payment\Domain\Exception\BonusCalculationPolicyCanNotBeCreatedException;
use App\Payment\Domain\Factory\BonusCalculationPolicyFactory;
use App\Payment\Domain\Model\DepartmentBonusRule;
use App\Payment\Domain\Policy\BonusCalculationPolicy;
use PHPUnit\Framework\TestCase;

final class BonusCalculationPolicyFactoryTest extends TestCase
{

    public function testCreate(): void
    {
        $factory = new BonusCalculationPolicyFactory();

        $policy = $factory->create(
            new DepartmentBonusRule(
                1,
                ['class' => 'App\Tests\unit\Payment\Domain\Factory\BonusCalculationPolicyFake', 'value' => 167],
                'fixed'
            )
        );

        self::assertInstanceOf(BonusCalculationPolicy::class, $policy);
        self::assertEquals(new BonusCalculationPolicyFake(167), $policy);
    }

    public function testInvalidConfigurationStructure(): void
    {
        $factory = new BonusCalculationPolicyFactory();

        $this->expectException(BonusCalculationPolicyCanNotBeCreatedException::class);

        $factory->create(
            new DepartmentBonusRule(
                1,
                ['class' => 'App\Tests\unit\Payment\Domain\Factory\BonusCalculationPolicyFake', 'fake_field' => 167],
                'fixed'
            )
        );
    }
}
