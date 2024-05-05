<?php

declare(strict_types=1);

namespace App\Payment\Domain\Model;

use App\Payment\Domain\Policy\BonusCalculationPolicy;
use App\Payment\Domain\System\DateTimeProviderInterface;
use DateTimeInterface;

final class Employee
{
    public function __construct(
        private readonly int $id,
        private string $name,
        private string $surname,
        private int $departmentId,
        private float $baseSalary,
        private DateTimeInterface $hiringDate
    ) {
    }

    public function calculateSalary(
        BonusCalculationPolicy $bonusCalculationPolicy,
        DateTimeProviderInterface $dateTimeProvider
    ): float {
        $yearOfExperience = ($this->hiringDate->diff($dateTimeProvider->getCurrentDate()))->y;

        return $this->baseSalary + $bonusCalculationPolicy->calculateBonus($this->baseSalary, $yearOfExperience);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getDepartmentId(): int
    {
        return $this->departmentId;
    }

    public function getBaseSalary(): float
    {
        return $this->baseSalary;
    }

    public function getHiringDate(): DateTimeInterface
    {
        return $this->hiringDate;
    }
}
