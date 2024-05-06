<?php

namespace App\Payment\Domain\Factory;

use App\Payment\Domain\Model\DepartmentBonusRule;
use App\Payment\Domain\Policy\BonusCalculationPolicy;
use App\Payment\Domain\Exception\BonusCalculationPolicyCanNotBeCreatedException;

interface BonusCalculationPolicyFactoryInterface
{
    /**
     * @throws BonusCalculationPolicyCanNotBeCreatedException
     */
    public function create(DepartmentBonusRule $bonusRule): BonusCalculationPolicy;
}