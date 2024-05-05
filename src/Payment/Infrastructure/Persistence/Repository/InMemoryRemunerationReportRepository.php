<?php

namespace App\Payment\Infrastructure\Persistence\Repository;

use App\Payment\Domain\Model\RemunerationReport;
use App\Payment\Domain\Repository\RemunerationReportRepositoryInterface;

class InMemoryRemunerationReportRepository implements RemunerationReportRepositoryInterface
{
    private array $remunerations;

    public function save(RemunerationReport ...$remunerationReports): void
    {
        foreach ($remunerationReports as $i => $remunerationReport) {
            $key = $i;

            if (isset($this->remunerations[$i])) {
                $key = count($this->remunerations);
            }

            $this->remunerations[$key] = $remunerationReport;
        }
    }

    public function findAll(): array
    {
        return $this->remunerations;
    }
}