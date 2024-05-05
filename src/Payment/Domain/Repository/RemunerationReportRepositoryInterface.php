<?php

namespace App\Payment\Domain\Repository;

use App\Payment\Domain\Model\RemunerationReport;

interface RemunerationReportRepositoryInterface
{
    public function save(RemunerationReport ...$remunerationReport): void;
    public function findAll(): array;
}