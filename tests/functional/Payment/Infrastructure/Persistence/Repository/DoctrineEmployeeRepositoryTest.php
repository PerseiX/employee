<?php

declare(strict_types=1);

namespace App\Tests\unit\Payment\Infrastructure\Persistence\Repository;

use App\Payment\Domain\Model\Employee;
use App\Payment\Infrastructure\Persistence\Repository\DoctrineEmployeeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DoctrineEmployeeRepositoryTest extends KernelTestCase
{
    private readonly DoctrineEmployeeRepository $employeeRepository;
    private readonly EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->employeeRepository = $container->get(DoctrineEmployeeRepository::class);

        $this->cleanUpDatabase();
    }

    public function testSuccess(): void
    {
        $hiring1 = new DateTimeImmutable('2010-10-01');
        $hiring2 = new DateTimeImmutable('2018-01-01');

        $this->employeeRepository->save($employee1 = new Employee(1, 'Jan', 'Kowalski', 1, 1500, $hiring1));
        $this->employeeRepository->save($employee2 = new Employee(2, 'Test', 'Testowy', 2, 2500, $hiring2));

        self::assertEquals(
            [
                $employee1,
                $employee2
            ],
            $this->employeeRepository->findAll()
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->cleanUpDatabase();
    }

    private function cleanUpDatabase(): void
    {
        $this->entityManager->createQuery('DELETE FROM ' . Employee::class)->execute();

        $this->entityManager->getConnection()
            ->prepare('ALTER TABLE employees AUTO_INCREMENT = 1')
            ->executeStatement();
    }
}
