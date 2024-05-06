<?php

declare(strict_types=1);

namespace App\Payment\Domain\Factory;

use App\Payment\Domain\Exception\BonusCalculationPolicyCanNotBeCreatedException;
use App\Payment\Domain\Model\DepartmentBonusRule;
use App\Payment\Domain\Policy\BonusCalculationPolicy;
use Throwable;

final readonly class BonusCalculationPolicyFactory implements BonusCalculationPolicyFactoryInterface
{
    /**
     * @throws BonusCalculationPolicyCanNotBeCreatedException
     */
    public function create(DepartmentBonusRule $bonusRule): BonusCalculationPolicy
    {
        $className = $bonusRule->configuration['class'] ?? null;

        try {
            return $className::createFromConfig($bonusRule->configuration);
        } catch (Throwable $exception) {
            throw new BonusCalculationPolicyCanNotBeCreatedException();
        }
    }
}
