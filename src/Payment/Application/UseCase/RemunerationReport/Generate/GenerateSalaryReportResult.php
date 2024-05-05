<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\RemunerationReport\Generate;

final class GenerateSalaryReportResult
{
    private const DEPARTMENT_NOT_FOUND = 'department_not_found';
    private const DEPARTMENT_BONUS_RULE_NOT_FOUND = 'department_bonus_rule_not_found';
    private const BONUS_CALCULATOR_CAN_NOT_BE_CREATED = 'bonus_calculator_can_not_be_created';

    private bool $success;
    private ?string $reason;

    private function __construct(bool $success, string $reason = null)
    {
        $this->success = $success;
        $this->reason = $reason;
    }

    public static function success(): self
    {
        return new self(true);
    }

    public static function departmentNotFound(): self
    {
        return new self(false, self::DEPARTMENT_NOT_FOUND);
    }

    public static function departmentBonusRuleNotFound(): self
    {
        return new self(false, self::DEPARTMENT_BONUS_RULE_NOT_FOUND);
    }

    public static function bonusCalculatorCanNotBeCreated(): self
    {
        return new self(false, self::BONUS_CALCULATOR_CAN_NOT_BE_CREATED);
    }


    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }
}
