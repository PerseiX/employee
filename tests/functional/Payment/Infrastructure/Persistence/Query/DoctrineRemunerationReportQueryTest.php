<?php

declare(strict_types=1);

namespace App\Tests\functional\Payment\Infrastructure\Persistence\Query;

use App\Payment\Application\Query\RemunerationReports\Filter;
use App\Payment\Application\Query\RemunerationReports\Order;
use App\Payment\Domain\Model\RemunerationReport;
use App\Payment\Infrastructure\Persistence\Query\DoctrineRemunerationReportQuery;
use App\Payment\Infrastructure\Persistence\Repository\DoctrineRemunerationReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DoctrineRemunerationReportQueryTest extends KernelTestCase
{
    private readonly DoctrineRemunerationReportQuery $doctrineRemunerationReportQuery;
    private readonly DoctrineRemunerationReportRepository $remunerationReportRepository;
    private readonly EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->doctrineRemunerationReportQuery = $container->get(DoctrineRemunerationReportQuery::class);
        $this->remunerationReportRepository = $container->get(DoctrineRemunerationReportRepository::class);

        $this->cleanUpDatabase();
    }

    #[DataProvider('filterAndOrderProvider')]
    public function testSuccess(Filter $filter, Order $order, array $remunerationExpectedIds): void
    {
        $this->remunerationReportRepository->save(
            $remuneration1 = new RemunerationReport(1, 'Jan', 'Kowalski', 'HR', 1650.0, 150.50, 'percentage', 1800.50),
            $remuneration2 = new RemunerationReport(2, 'John', 'Testowy', 'IT', 2250.0, 100.00, 'fixed', 2350.00),
            $remuneration3 = new RemunerationReport(3, 'Awesome', 'Worker', 'IT', 3500.0, 255.00, 'fixed', 3755.00),
        );

        $remunerations = $this->doctrineRemunerationReportQuery->execute($filter, $order);

        $expectedOutput = [];
        foreach ($remunerationExpectedIds as $remunerationExpectedId) {
            $expectedOutput[] = ${'remuneration'.$remunerationExpectedId}->toArray();
        }

        self::assertSame($expectedOutput, $remunerations);
    }

    public static function filterAndOrderProvider(): iterable
    {
        yield 'filters and order empty' => [
            'filter' => new Filter(),
            'order'  => new Order(),
            'remunerationExpectedIds' => [1, 2, 3]
        ];

        yield 'criteria not matched' => [
            'filter' => new Filter('Not existed name'),
            'order'  => new Order(),
            'remunerationExpectedIds' => []
        ];

        yield 'filter by name, no order' => [
            'filter' => new Filter('Awesome'),
            'order'  => new Order(),
            'remunerationExpectedIds' => [3]
        ];

        yield 'filter by surname, no order' => [
            'filter' => new Filter(surname: 'Kowalski'),
            'order'  => new Order(),
            'remunerationExpectedIds' => [1]
        ];

        yield 'filter by department, no order' => [
            'filter' => new Filter(department: 'IT'),
            'order'  => new Order(),
            'remunerationExpectedIds' => [2,3]
        ];

        yield 'all records sorted ascending by name' => [
            'filter' => new Filter(),
            'order'  => new Order(field: 'name'),
            'remunerationExpectedIds' => [3,1,2]
        ];

        yield 'all records sorted descending by name' => [
            'filter' => new Filter(),
            'order'  => new Order('desc',  'name'),
            'remunerationExpectedIds' => [2,1,3]
        ];

        yield 'IT department sorted descending by surname' => [
            'filter' => new Filter(department: 'IT'),
            'order'  => new Order('desc',  'surname'),
            'remunerationExpectedIds' => [3,2]
        ];
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
