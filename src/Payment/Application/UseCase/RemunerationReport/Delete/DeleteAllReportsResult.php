<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\RemunerationReport\Delete;

final class DeleteAllReportsResult
{
    private function __construct(private readonly bool $success)
    {
    }

    public static function success(): self
    {
        return new self(true);
    }
}
