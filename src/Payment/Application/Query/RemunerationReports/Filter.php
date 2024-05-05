<?php

declare(strict_types=1);

namespace App\Payment\Application\Query\RemunerationReports;

final class Filter
{
    public function __construct(
        public readonly string|null $name = null,
        public readonly string|null $surname = null,
        public readonly string|null $department = null,
    ) {

    }
}
