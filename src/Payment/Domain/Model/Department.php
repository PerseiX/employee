<?php

declare(strict_types=1);

namespace App\Payment\Domain\Model;

final class Department
{
    public function __construct(
        private int $id,
        private readonly string $name
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
}
