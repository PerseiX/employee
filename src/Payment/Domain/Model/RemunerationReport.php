<?php

declare(strict_types=1);

namespace App\Payment\Domain\Model;

final class RemunerationReport
{
    public function __construct(
        private int $id,
        private readonly string $name,
        private readonly string $surname,
        private readonly string $department,
        private readonly float $remuneration,
        private readonly float $bonus,
        private readonly string $bonusType,
        private readonly float $finalSalary,
    ) {
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

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function getRemuneration(): float
    {
        return $this->remuneration;
    }

    public function getBonus(): float
    {
        return $this->bonus;
    }

    public function getBonusType(): string
    {
        return $this->bonusType;
    }

    public function getFinalSalary(): float
    {
        return $this->finalSalary;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'department' => $this->department,
            'remuneration' => number_format((float)$this->remuneration, 2, '.', ''),
            'bonus' => number_format((float)$this->bonus, 2, '.', ''),
            'bonusType' => $this->bonusType,
            'finalSalary' => number_format((float)$this->finalSalary, 2, '.', ''),
        ];
    }
}
