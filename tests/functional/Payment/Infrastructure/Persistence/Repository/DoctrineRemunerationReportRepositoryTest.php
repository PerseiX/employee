<?php

declare(strict_types=1);

namespace App\Tests\unit\Payment\Infrastructure\Persistence\Repository;

use App\Payment\Domain\Model\RemunerationReport;
use App\Payment\Infrastructure\Persistence\Repository\DoctrineRemunerationReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DoctrineRemunerationReportRepositoryTest extends KernelTestCase
{
    private readonly DoctrineRemunerationReportRepository $remunerationReportRepository;
    private readonly EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->remunerationReportRepository = $container->get(DoctrineRemunerationReportRepository::class);

        $this->cleanUpDatabase();
    }

    public function testSuccess(): void
    {
        $this->remunerationReportRepository->save(
            $remuneration1 = new RemunerationReport(1, 'Jan', 'Kowalski', 'HR', 1650.0, 150.5, 'percentage', 1800.5),
            $remuneration2 = new RemunerationReport(2, 'John', 'Testowy', 'IT', 2250.0, 100.0, 'fixed', 2350),
        );

        self::assertEquals(
            [$remuneration1, $remuneration2],
            $this->remunerationReportRepository->findAll()
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->cleanUpDatabase();
    }

    private function cleanUpDatabase(): void
    {
        $this->entityManager->createQuery('DELETE FROM ' . RemunerationReport::class)->execute();
    }
}
