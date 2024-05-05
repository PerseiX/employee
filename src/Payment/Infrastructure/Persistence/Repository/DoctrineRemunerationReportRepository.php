<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Persistence\Repository;

use App\Payment\Domain\Model\RemunerationReport;
use App\Payment\Domain\Repository\RemunerationReportRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineRemunerationReportRepository implements RemunerationReportRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(RemunerationReport ...$remunerationReports): void
    {
        foreach ($remunerationReports as $remunerationReport) {
            $this->entityManager->persist($remunerationReport);
        }

        $this->entityManager->flush();
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(RemunerationReport::class)->findAll();
    }
}
