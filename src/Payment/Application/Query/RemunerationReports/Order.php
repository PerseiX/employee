<?php

declare(strict_types=1);

namespace App\Payment\Application\Query\RemunerationReports;

final class Order
{
    public function __construct(
        public readonly string $direction = 'ASC',
        public readonly string $field = 'id'
    ){

    }
}
